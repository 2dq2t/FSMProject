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
                'only' => ['update','manageacc','changepass' ],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [ 'update','manageacc','changepass'],
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

    public function actionManageacc($id){

        if (Yii::$app->user->id != $id) {
            return $this->redirect('index.php?r=customer/manageacc&id='.Yii::$app->user->id.'');
        }
        return $this->render('manageacc');
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

        if (Yii::$app->user->id != $id) {
            return $this->redirect('index.php?r=customer/changepass&id='.Yii::$app->user->id.'');
        }

        $modelCustomer = $this->findModel($id);
        $modelCustomer->scenario = 'changePassword';
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

    public function actionChangeaddress($id){

        $modelCustomer = $this->findModel($id);
        $modelUpdateAddress = Address::find()->where(['id' => $modelCustomer->address_id])->one();

        //When address_id of Customer equal null => add new address
        if($modelUpdateAddress == null) {
            $modelAddress = new Address();
            $modelCity = new City();
            $modelDistrict = new District();

            if($modelAddress->load(Yii::$app->request->post())){
                $transaction = Yii::$app->db->beginTransaction();
                try{
                    if($modelAddress->save()){
                        $modelCustomer->address_id = $modelAddress->id;
                        $modelCustomer->save();
                        $transaction->commit();

                        Yii::$app->getSession()->setFlash('successful', [
                            'type' => Alert::TYPE_SUCCESS,
                            'duration' => 3000,
                            'icon' => 'fa fa-plus',
                            'message' => Yii::t('app', 'Your address has been saved.'),
                            'title' => Yii::t('app', 'New Address'),
                        ]);
                        return $this->redirect(['changeaddress', 'id' => $modelCustomer->id]);
                    }
                }catch (Exception $e){
                    $errors = $modelCustomer->getErrors();
                    foreach($errors as $error) {
                        Yii::$app->getSession()->setFlash('failed', [
                            'type' => Alert::TYPE_DANGER,
                            'duration' => 3000,
                            'icon' => 'fa fa-plus',
                            'message' => $error[0],
                            'title' => Yii::t('app', 'Add Address'),
                        ]);
                    }

                    $transaction->rollBack();
                }
            }else{
                return $this->render('changeaddress', [
                    'modelAddress' => $modelAddress,
                    'modelCity' => $modelCity,
                    'modelDistrict' => $modelDistrict,
                ]);
            }
        }else{
            $modelUpdateDistrict = District::find()->where(['id' => $modelUpdateAddress->district_id])->one();
            $modelUpdateCity = City::find()->where(['id'=>$modelUpdateDistrict->city_id])->one();

            if($modelUpdateAddress->load(Yii::$app->request->post())){
                $transaction = Yii::$app->db->beginTransaction();
                try{
                    if($modelUpdateAddress->save()){
                        $modelCustomer->address_id = $modelUpdateAddress->id;
                        $transaction->commit();

                        Yii::$app->getSession()->setFlash('successful', [
                            'type' => Alert::TYPE_SUCCESS,
                            'duration' => 3000,
                            'icon' => 'fa fa-plus',
                            'message' => Yii::t('app', 'Your address has been saved.'),
                            'title' => Yii::t('app', 'Update Address'),
                        ]);
                        return $this->redirect(['changeaddress', 'id' => $modelCustomer->id]);
                    }
                }catch (Exception $e){
                    $errors = $modelCustomer->getErrors();
                    foreach($errors as $error) {
                        Yii::$app->getSession()->setFlash('failed', [
                            'type' => Alert::TYPE_DANGER,
                            'duration' => 3000,
                            'icon' => 'fa fa-plus',
                            'message' => $error[0],
                            'title' => Yii::t('app', 'Update Address'),
                        ]);
                    }

                    $transaction->rollBack();
                }
            }else{
                return $this->render('changeaddress', [
                    'modelUpdateAddress' => $modelUpdateAddress,
                    'modelUpdateCity' => $modelUpdateCity,
                    'modelUpdateDistrict' => $modelUpdateDistrict,
                ]);
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
                    $out = District::getOptionsByDistrict($city_id);
                    echo Json::encode(['output' => $out, 'selected' => '']);
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
