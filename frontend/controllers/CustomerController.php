<?php

namespace frontend\controllers;

use Yii;
use common\models\Customer;
use common\models\Address;
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
use kartik\alert\Alert;

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
        $modelGuest = Guest::find()->where(['id' => $modelCustomer->guest_id])->one();

        if($modelCustomer->load(Yii::$app->request->post())
            && $modelGuest->load(Yii::$app->request->post())){

            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($modelGuest->save()) {

                    $avatar = UploadedFile::getInstance($modelCustomer,'avatar');

                    if($avatar) {
                        $ext = $avatar->extension;
                        // generate a unique file name
                        $modelCustomer->avatar = Yii::$app->security->generateRandomString().".{$ext}";
                    }else{
                        $modelCustomer->avatar = $modelCustomer->oldAttributes['avatar'];
                    }

                    $modelCustomer->guest_id = $modelGuest->id;
                    if ($modelCustomer->UpdateCustomer($id)) {
                        if($avatar){
                            // directory to save image in local
                            $dir = Yii::getAlias('@frontend/web/uploads/users/avatar/' . $modelCustomer->id);
                            FileHelper::removeDirectory($dir);
                            FileHelper::createDirectory($dir);
                                $avatar->saveAs($dir . '/' . $modelCustomer->avatar);
                        }

                        $transaction->commit();

                        Yii::$app->getSession()->setFlash('successful', [
                            'type' => Alert::TYPE_SUCCESS,
                            'duration' => 3000,
                            'icon' => 'fa fa-plus',
                            'message' => Yii::t('app', 'Your Profile has been saved.'),
                            'title' => Yii::t('app', 'Update Profile'),
                        ]);

                        return $this->redirect(['update', 'id' => $modelCustomer->id]);

                    } else {

                        $errors = $modelCustomer->getErrors();
                        foreach($errors as $error) {
                            Yii::$app->getSession()->setFlash('success', [
                                'type' => Alert::TYPE_DANGER,
                                'duration' => 3000,
                                'icon' => 'fa fa-plus',
                                'message' => $error[0],
                                'title' => Yii::t('app', 'Update Profile'),
                            ]);
                        }

                        return $this->render('update', [
                            'modelCustomer' => $modelCustomer,
                            'modelGuest' => $modelGuest,
                        ]);
                    }
                } else {

                    $errors = $modelCustomer->getErrors();
                    foreach($errors as $error) {
                        Yii::$app->getSession()->setFlash('success', [
                            'type' => Alert::TYPE_DANGER,
                            'duration' => 3000,
                            'icon' => 'fa fa-plus',
                            'message' => $error[0],
                            'title' => Yii::t('app', 'Update Profile'),
                        ]);
                    }

                    return $this->render('update', [
                        'modelCustomer' => $modelCustomer,
                        'modelGuest' => $modelGuest,
                    ]);
                }
            }catch (Exception $e){
                $transaction->rollBack();

                $errors = $modelCustomer->getErrors();
                foreach($errors as $error) {
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => Alert::TYPE_DANGER,
                        'duration' => 3000,
                        'icon' => 'fa fa-plus',
                        'message' => $error[0],
                        'title' => Yii::t('app', 'Update Profile'),
                    ]);
                }
            }
        }else{

            if ($modelCustomer->dob) {
                $modelCustomer->dob = date('m/d/Y', $modelCustomer->dob);
            } else {
                $modelCustomer->dob = NULL;
            }
            return $this->render('update', [
                'modelCustomer' => $modelCustomer,
                'modelGuest' => $modelGuest,
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

                    Yii::$app->getSession()->setFlash('successful', [
                        'type' => Alert::TYPE_SUCCESS,
                        'duration' => 3000,
                        'icon' => 'fa fa-plus',
                        'message' => Yii::t('app', 'Your password has been saved.'),
                        'title' => Yii::t('app', 'Update Profile'),
                    ]);

                    return $this->redirect(['update', 'id' => $modelCustomer->id]);
                }else{

                    $errors = $modelCustomer->getErrors();
                    foreach($errors as $error) {
                        Yii::$app->getSession()->setFlash('success', [
                            'type' => Alert::TYPE_DANGER,
                            'duration' => 3000,
                            'icon' => 'fa fa-plus',
                            'message' => $error[0],
                            'title' => Yii::t('app', 'Add User'),
                        ]);
                    }

                    return $this->render('changepass',[
                        'modelCustomer' => $modelCustomer,
                    ]);
                }
            }catch (Exception $e){
                $errors = $modelCustomer->getErrors();
                foreach($errors as $error) {
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => Alert::TYPE_DANGER,
                        'duration' => 3000,
                        'icon' => 'fa fa-plus',
                        'message' => $error[0],
                        'title' => Yii::t('app', 'Add User'),
                    ]);
                }

                $transaction->rollBack();
            }
        }

        return $this->render('changepass',[
            'modelCustomer' => $modelCustomer,
        ]);
    }

    public function actionSubcat() {
        $out = [];
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
