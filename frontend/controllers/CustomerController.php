<?php

namespace frontend\controllers;

use Yii;
use common\models\Customer;
use common\models\Address;
use common\models\Ward;
use common\models\District;
use common\models\City;
use common\models\Guest;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\JSon;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\base\Exception;

/**
 * CustomerController implements the CRUD actions for Customer model.
 */
class CustomerController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['update' ],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [ 'update'],
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
     * Lists all Customer models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Customer::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Customer model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Customer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Customer();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Customer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if (Yii::$app->user->id != $id) {
            return $this->redirect('index.php?r=customer/update&id='.Yii::$app->user->id.'');
        }

        $modelCustomer = $this->findModel($id);
        $modelGuest = Guest::find()->where(['customer_id' => $id])->one();
        $modelAddress = Address::find()->where(['id' => $modelCustomer->address_id])->one();
        $modelWard = Ward::find()->where(['id' => $modelAddress->ward_id])->one();
        $modelDistrict = District::find()->where(['id' => $modelWard->district_id])->one();
        $modelCity = City::find()->where(['id'=>$modelDistrict->city_id])->one();

        if($modelAddress->load(Yii::$app->request->post())
            && $modelCustomer->load(Yii::$app->request->post())
            && $modelGuest->load(Yii::$app->request->post())){

            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($modelAddress->save()) {

                    $avatar = UploadedFile::getInstance($modelCustomer,'avatar');

                    if($avatar) {
                        $ext = $avatar->extension;

                        // generate a unique file name
                        $modelCustomer->avatar = Yii::$app->security->generateRandomString().".{$ext}";

                        // the path to save file, you can set an uploadPath
                        // in Yii::$app->params (as used in example below)
//                        $path = Yii::$app->params['uploadPath'] . $model->avatar;
                    }else{
                        $modelCustomer->avatar = null;
                    }

                    //return var_dump($modelCustomer->avatar);

                    $modelCustomer->address_id = $modelAddress->id;
                    if ($modelCustomer->UpdateCustomer($id)) {

                        // directory to save image in local
                        $dir = Yii::getAlias('@frontend/web/uploads/users/avatar/' . $modelCustomer->id);
                        FileHelper::removeDirectory($dir);
                        FileHelper::createDirectory($dir);
                        // path to save database
//                        $path = 'frontend/uploads/users/avatar/' . $model->id . '/';
                        if($avatar){
                            $avatar->saveAs($dir . '/' . $modelCustomer->avatar);
                        }
                        $modelGuest->customer_id = $modelCustomer->id;
                        $modelGuest->save();

                        $transaction->commit();

                        Yii::$app->getSession()->setFlash('successful', 'User Record has been edited.');

                        return $this->redirect('index.php?r=customer/update&id='.Yii::$app->user->id.'');

                    } else {
                        return $this->render('update', [
                            'modelCustomer' => $modelCustomer,
                            'modelGuest' => $modelGuest,
                            'modelAddress' => $modelAddress,
                            'modelWard' => $modelWard,
                            'modelDistrict' => $modelDistrict,
                            'modelCity' => $modelCity,
                        ]);
                    }
                } else {
                    return $this->render('update', [
                        'modelCustomer' => $modelCustomer,
                        'modelGuest' => $modelGuest,
                        'modelAddress' => $modelAddress,
                        'modelWard' => $modelWard,
                        'modelDistrict' => $modelDistrict,
                        'modelCity' => $modelCity,
                    ]);
                }
            }catch (Exception $e){
                $transaction->rollBack();
            }
        }else{
            return $this->render('update', [
                'modelCustomer' => $modelCustomer,
                'modelGuest' => $modelGuest,
                'modelAddress' => $modelAddress,
                'modelWard' => $modelWard,
                'modelDistrict' => $modelDistrict,
                'modelCity' => $modelCity,
            ]);
        }
    }

    public function actionChangepass($id){
        $modelCustomer = $this->findModel($id);
        $modelCustomer->scenario = 'changepass';
        if($modelCustomer->load(Yii::$app->request->post())){
            $transaction = Yii::$app->db->beginTransaction();
            try{
                if($modelCustomer->ChangePassword($id)){
                    $transaction->commit();

                    Yii::$app->getSession()->setFlash('successful', 'Your password has been edited.');

                    return $this->redirect('index.php?r=customer/update&id='.Yii::$app->user->id.'');
                }else{
                    return $this->render('changepass',[
                        'modelCustomer' => $modelCustomer,
                    ]);
                }
            }catch (Exception $e){
                $transaction->rollBack();
            }
        }

        return $this->render('changepass',[
            'modelCustomer' => $modelCustomer,
        ]);
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
                    $out = District::getOptionsByDistrict($city_id);
                    echo Json::encode(['output' => $out, 'selected' => '']);
                    return;
                }
            }
            echo Json::encode(['output'=>'', 'selected'=>'']);
        }
    }

    public function actionGetward($id = null) {
        if (isset($id)) {
            $countWard= Ward::find()
                ->where(['district_id' => $id])
                ->count();

            $wards = Ward::find()
                ->where(['district_id' => $id])
                ->all();

            if($countWard>0){
                foreach($wards as $ward){
                    echo "<option value='".$ward->id."'>".$ward->name."</option>";
                }
            }
            else{
                echo "<option>-</option>";
            }
        } else {
            if (isset($_POST['depdrop_parents'])) {
                $ids = $_POST['depdrop_parents'];
                $cat_id = empty($ids[0]) ? null : $ids[0];
                $subcat_id = empty($ids[1]) ? null : $ids[1];
                if ($cat_id != null && $subcat_id != null) {
                    $data = Ward::getOptionsByWard($subcat_id);
                    echo Json::encode(['output'=>$data, 'selected'=>'']);
                    return;
                }
            }
            echo Json::encode(['output'=>'', 'selected'=>'']);
        }
    }

    /**
     * Deletes an existing Customer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Customer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Customer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Customer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
