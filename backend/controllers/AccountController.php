<?php
/**
 * Created by PhpStorm.
 * User: TuDA
 * Date: 8/17/2015
 * Time: 11:39 PM
 */

namespace backend\controllers;

use yii\filters\AccessControl;
use backend\components\ParserDateTime;
use common\models\Address;
use common\models\City;
use common\models\District;
use Yii;
use backend\models\Employee;
use yii\base\Exception;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

class AccountController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'assign' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['update'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['logout'],
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Update Profile of Employee.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->user->id != $id) {
            return $this->redirect('index.php?r=account/update&id=' . Yii::$app->user->id . '');
        }

        $model = $this->findModel($id);
        $model->scenario = 'updateProfile';
        $address = Address::find()->where(['id' => $model->address_id])->one();
        $district = District::find()->where(['id' => $address->district_id])->one();
        $city = City::find()->where(['id' => $district->city_id])->one();

        $model->dob = date('d/m/Y', $model->dob);

        if ($model->load(Yii::$app->request->post())
            && $address->load(Yii::$app->request->post())
        ) {
            $oldAddress = $address->oldAttributes;
            $oldEmployee = $model->oldAttributes;

            if(!UploadedFile::getInstance($model, 'image')) {
                $model->image = $model->oldAttributes['image'];
            }
            $model->dob = ParserDateTime::parseToTimestamp($model->dob);

            // Begin transaction
            $transaction = Yii::$app->db->beginTransaction();
            $errors = [];
            try{
                // Save address info to database
                if($address->save()) {
                    $image = UploadedFile::getInstance($model, 'image');

                    if($image) {
                        $ext = $image->extension;
                        // generate a unique file name
                        $model->image = Yii::$app->security->generateRandomString().".{$ext}";
                    }

                    if ($model->save()) {
                        if ($image) {
                            // directory to save image in local
                            $dir = Yii::getAlias('@backend/web/uploads/employees/image/' . $model->id);
                            FileHelper::removeDirectory($dir);
                            FileHelper::createDirectory($dir);
                            $image->saveAs($dir . '/' . $model->image);
                        }

                        $transaction->commit();

                        Yii::$app->getSession()->setFlash('success', [
                            'type' => 'success',
                            'duration' => 3000,
                            'icon' => 'fa fa-plus',
                            'message' => Yii::t('app', 'UpdateProfileMsg01'),
                            'title' => Yii::t('app', 'ChangeAccInfoLabel'),
                        ]);

                        if (Yii::$app->request->post('action', 'save')) {
                            return $this->redirect(['update','id' => $id]);
                        }
                    }else{
                        if ($transaction->getIsActive()) {
                            $transaction->rollBack();
                        }

                        if ($model->dob != '') {
                            $model->dob = date('d/m/Y', $model->dob);
                        }

                        // get errors
                        Yii::$app->getSession()->setFlash('error', [
                            'type' => 'error',
                            'duration' => 0,
                            'icon' => 'fa fa-plus',
                            'message' => !empty($errors) ? $errors : current($model->getFirstErrors()) || Yii::t('app', 'Employee_Update_Error_Msg'),
                            'title' => Yii::t('app', 'ChangeAccInfoLabel'),
                        ]);

                        return $this->render('update', [
                            'model' => $model,
                            'address' => $address,
                            'city' => $city
                        ]);
                    }
                }else{
                    if ($transaction->getIsActive()) {
                        $transaction->rollBack();
                    }
                    if ($model->dob != '') {
                        $model->dob = date('d/m/Y', $model->dob);
                    }

                    // get errors
                    Yii::$app->getSession()->setFlash('error', [
                        'type' => 'error',
                        'duration' => 0,
                        'icon' => 'fa fa-plus',
                        'message' => current($address->getFirstErrors()) ? current($address->getFirstErrors()) : Yii::t('app', 'Employee_Update_Error_Msg'),
                        'title' => Yii::t('app', 'ChangeAccInfoLabel'),
                    ]);

                    return $this->render('update', [
                        'model' => $model,
                        'address' => $address,
                        'city' => $city
                    ]);
                }
            }catch (Exception $e){
                if ($transaction->getIsActive()) {
                    $transaction->rollBack();
                }

                Yii::$app->getSession()->setFlash('error', [
                    'type' => 'error',
                    'duration' => 0,
                    'icon' => 'fa fa-plus',
                    'message' => !empty($errors) != '' ? $errors[0] : $e->getMessage(),
                    'title' => Yii::t('app', 'ChangeAccInfoLabel'),
                ]);

                return $this->render('update', [
                    'model' => $model,
                    'address' => $address,
                    'city' => $city
                ]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'address' => $address,
            'city' => $city,
        ]);
    }

    /**
     * Change Password Profile of Employee.
     * @param string $id
     * @return mixed
     */
    public function actionChange($id){

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $new_password = explode(":", $data['new_password']);
            $model = $this->findModel($id);
            $transaction = Yii::$app->db->beginTransaction();
            try{
                $model->password = Yii::$app->security->generatePasswordHash($new_password[0]);
                if($model->save()){
                    $transaction->commit();
                        Yii::$app->getSession()->setFlash('success', [
                            'type' => 'success',
                            'duration' => 3000,
                            'icon' => 'fa fa-plus',
                            'message' => Yii::t('app', 'ChangePasswordMsg01'),
                            'title' => Yii::t('app', 'ChangePassInfoLabel'),
                        ]);
                }else{
                    $errors = $model->getErrors();
                    foreach ($errors as $error) {
                        Yii::$app->getSession()->setFlash('error', [
                            'type' => 'error',
                            'duration' => 3000,
                            'icon' => 'fa fa-plus',
                            'message' => $error[0],
                            'title' => Yii::t('app', 'ChangePassInfoLabel'),
                        ]);
                    }
                }
            }catch(Exception $e){
                $errors = $model->getErrors();
                foreach ($errors as $error) {
                    Yii::$app->getSession()->setFlash('error', [
                        'type' => 'error',
                        'duration' => 3000,
                        'icon' => 'fa fa-plus',
                        'message' => $error[0],
                        'title' => Yii::t('app', 'ChangePassInfoLabel'),
                    ]);
                }
            }
        }
    }

    public function actionGetdistrict($id = null) {
        if (isset($id)) {
            $countDistrict= District::find()
                ->where(['city_id' => $id])
                ->count();

            $districts = District::find()
                ->where(['city_id' => $id])
                ->all();

            if($countDistrict>0){
                foreach($districts as $district){
                    echo "<option value='".$district->id."'>".$district->name."</option>";
                }
            }
            else{
                echo "<option>-</option>";
            }
        } else {
            if (isset($_POST['depdrop_parents'])) {
                $parents = $_POST['depdrop_parents'];
                if ($parents != null) {
                    $city_id = $parents[0];
                    $data = District::find()->where(['city_id'=>$city_id])->select(['id','name'])->asArray()->all();
                    $out = (count($data) == 0) ? ['' => ''] : $data;
                    echo Json::encode(['output' => $out, 'selected' => '']);
                    return;
                }
            }
            echo Json::encode(['output'=>'', 'selected'=>'']);
        }
    }

    /**
     * Finds the Employee model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Employee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Employee::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}