<?php

namespace backend\controllers;

use common\models\City;
use common\models\District;
use common\models\Guest;
use common\models\Ward;
use common\models\Address;
use kartik\alert\Alert;
use Yii;
use common\models\Customer;
use common\models\CustomerSearch;
use yii\base\Exception;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

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
        ];
    }

    /**
     * Lists all Customer models.
     * @return mixed
     */
    public function actionIndex()
    {
//        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $searchModel = new CustomerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//        var_dump($dataProvider);
//        return;
//        $dataProvider->setPagination(['pageSize' => 7]);

        // validate if there is a editable input saved via AJAX
        if (Yii::$app->request->post('hasEditable')) {

            $customer_id = Yii::$app->request->post('editableKey');
            $model = Customer::findOne($customer_id);

            if(!$model) {
                // store a default json response as desired by editable
                $message = Yii::t('app', 'The User do not exist.');
                echo $out = Json::encode(['output'=>'', 'message'=>$message]);
                return;
            }

            $post = [];
            $posted = current($_POST['Customer']);
            $post['Customer'] = $posted;

            // load model like any single model validation
            if ($model->load($post)) {

                $output = '';
                $message = '';

                if($model->save() && isset($posted['status'])) {
                    if ($posted['status'] == 1) {
                        $label_class = 'label-success';
                        $value = 'Active';
                    } else {
                        $value = 'Inactive';
                        $label_class = 'label-default';
                    }
                    $output = Html::tag(
                        'span', Yii::t('app', $value), ['class' => 'label ' . $label_class]
                    );
                } else {
                    $message = $model->errors;
                }

                $out = Json::encode(['output'=>$output, 'message'=>$message]);
            }
            // return ajax json encoded response and exit
            echo $out;
            return;
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
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
//        return $this->render('view', [
//            'model' => $this->findModel($id),
//        ]);
    }

    /**
     * Creates a new Customer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Customer();
        $model->scenario = 'adminCreate';
        $guest = new Guest();
        $address = new Address();
        $city = new City();

        // Load all file from post to model
        if ($model->load(Yii::$app->request->post())
            && $guest->load(Yii::$app->request->post())
            && $address->load(Yii::$app->request->post())) {
            // Begin transaction
            $transaction = Yii::$app->db->beginTransaction();
            try{
                // Save address info to database
                if($address->save() && $guest->save()) {

                    if ($model->password) {
                        $model->setPassword($model->password);
                    }

                    $avatar = UploadedFile::getInstance($model, 'avatar');

                    if($avatar) {
                        $ext = $avatar->extension;

                        // generate a unique file name
                        $model->avatar = Yii::$app->security->generateRandomString().".{$ext}";

                        // the path to save file, you can set an uploadPath
                        // in Yii::$app->params (as used in example below)
//                        $path = Yii::$app->params['uploadPath'] . $model->avatar;
                    }

                    $model->created_at = time();

                    if($model->dob) {
                        $model->dob = strtotime($model->dob);
                    } else {
                        $model->dob = NULL;
                    }
                    $model->address_id = $address->id;
                    $model->guest_id = $guest->id;
                    if ($model->save()) {
                        // directory to save image in local
                        $dir = Yii::getAlias('@frontend/web/uploads/users/avatar/' . $model->id);
                        FileHelper::createDirectory($dir);
                        // path to save database
//                        $path = 'uploads/users/avatar/' . $model->id . '/';
                        if ($avatar) {
                            $avatar->saveAs($dir . '/' . $model->avatar);
                        }

                        $transaction->commit();

                        Yii::$app->getSession()->setFlash('success', [
                            'type' => Alert::TYPE_SUCCESS,
                            'duration' => 3000,
                            'icon' => 'fa fa-plus',
                            'message' => Yii::t('app', 'User Record has been saved.'),
                            'title' => Yii::t('app', 'Add User'),
                        ]);

                        switch (Yii::$app->request->post('action', 'save')) {
                            case 'next':
                                return $this->redirect(['create']);
                            default:
                                return $this->redirect(['index']);
                        }
                    } else {
                        // if save to user error return create page
//                        $model->password = $password_return;
//                        $model->re_password = $re_password_return;
                        if ($model->dob != '') {
                            $model->dob = date('m/d/Y', $model->dob);
                        }

                        if($model->created_at != '') {
                            $model->created_at = date('m/d/Y', $model->created_at);
                        }
                        // get errors
                        $errors = $model->getErrors();
                        foreach($errors as $error) {
                            Yii::$app->getSession()->setFlash('success', [
                                'type' => Alert::TYPE_DANGER,
                                'duration' => 3000,
                                'icon' => 'fa fa-plus',
                                'message' => $error[0],
                                'title' => Yii::t('app', 'Add User'),
                            ]);
                        }

                        $model->password = null;

                        return $this->render('create', [
                            'model' => $model,
                            'guest' => $guest,
                            'address' => $address,
                            'city' => $city,
                        ]);
                    }
                } else {
                    if ($model->dob != '') {
                        $model->dob = date('m/d/Y', $model->dob);
                    }

                    if($model->created_at != '') {
                        $model->created_at = date('m/d/Y', $model->created_at);
                    }

                    // get errors
                    $errors = $address->getErrors();
                    foreach($errors as $error) {
                        Yii::$app->getSession()->setFlash('success', [
                            'type' => Alert::TYPE_DANGER,
                            'duration' => 3000,
                            'icon' => 'fa fa-plus',
                            'message' => $error[0],
                            'title' => Yii::t('app', 'Add User'),
                        ]);
                    }

                    $model->password = null;

                    return $this->render('create', [
                        'model' => $model,
                        'guest' => $guest,
                        'address' => $address,
                        'city' => $city,
                    ]);
                }
            } catch (Exception $e) {
                $transaction->rollBack();

                if ($model->dob != '') {
                    $model->dob = date('m/d/Y', $model->dob);
                }

                if($model->created_at != '') {
                    $model->created_at = date('m/d/Y', $model->created_at);
                }

                $errors = $address->getErrors();
                foreach($errors as $error) {
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => Alert::TYPE_DANGER,
                        'duration' => 3000,
                        'icon' => 'fa fa-plus',
                        'message' => $error[0],
                        'title' => Yii::t('app', 'Add User'),
                    ]);
                }

                $model->password = null;

                return $this->render('create', [
                    'model' => $model,
                    'guest' => $guest,
                    'address' => $address,
                    'city' => $city,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'guest' => $guest,
                'address' => $address,
                'city' => $city,
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
        $model = $this->findModel($id);
        $model->scenario = 'adminEdit';
        $model->password = null;
        $guest = Guest::find()->where(['id' => $model->guest_id])->one();
        $address = Address::find()->where(['id' => $model->address_id])->one();
        $district = District::find()->where(['id' => $address->district_id])->one();
        $city = City::find()->where(['id'=>$district->city_id])->one();

        // Load all file from post to model
        if ($model->load(Yii::$app->request->post())
            && $guest->load(Yii::$app->request->post())
            && $address->load(Yii::$app->request->post())) {

            // Begin transaction
            $transaction = Yii::$app->db->beginTransaction();
            try{
                // Save address info to database
                if($address->save() && $guest->save()) {

                    if ($model->password) {
                        $model->setPassword($model->password);
                    }

//                    if ($model->dob) {
//                        $model->dob = Yii::$app->request->post('Customer')['dob'];
                    $model->dob = strtotime($model->dob);
//                    } else {
//                        $model->dob = NULL;
//                    }

                    $avatar = UploadedFile::getInstance($model,'avatar');

                    if($avatar) {
                        $ext = $avatar->extension;

                        // generate a unique file name
                        $model->avatar = Yii::$app->security->generateRandomString().".{$ext}";
                    } else {
                        $model->avatar = $model->oldAttributes['avatar'];
                    }

                    if (Yii::$app->request->post('Customer')['status'] === '1') {
                        $model->status = 1;
                    } else {
                        $model->status = 0;
                    }

                    if (Yii::$app->request->post('Customer')['dob']) {
                        $model->dob = strtotime(Yii::$app->request->post('Customer')['dob']);
                    } else {
                        $model->dob = $model->oldAttributes['dob'];
                    }

                    if (Yii::$app->request->post('Customer')['password'] === '') {
                        $model->password = $model->oldAttributes['password'];
                    }

                    $model->updated_at = time();

                    if ($model->save()) {

                        if ($avatar) {
                            // directory to save image in local
                            $dir = Yii::getAlias('@frontend/web/uploads/users/avatar/' . $model->id);
                            FileHelper::removeDirectory($dir);
                            FileHelper::createDirectory($dir);
                            $avatar->saveAs($dir . '/' . $model->avatar);
                        }

                        $transaction->commit();

                        Yii::$app->getSession()->setFlash('success', [
                            'type' => Alert::TYPE_SUCCESS,
                            'duration' => 3000,
                            'icon' => 'fa fa-pencil',
                            'message' => Yii::t('app', 'User Record has been edited.'),
                            'title' => Yii::t('app', 'Edit User'),
                        ]);

                        return $this->redirect(['index']);
                    } else {
                        if ($model->dob) {
                            $model->dob = date('m/d/Y', $model->dob);
                        } else {
                            $model->dob = NULL;
                        }

                        $model->password = NULL;

                        // get errors
                        $errors = $model->getErrors();
                        foreach($errors as $error) {
                            Yii::$app->getSession()->setFlash('success', [
                                'type' => Alert::TYPE_DANGER,
                                'duration' => 3000,
                                'icon' => 'fa fa-pencil',
                                'message' => $error[0],
                                'title' => Yii::t('app', 'Edit User'),
                            ]);
                        }

                        return $this->render('update', [
                            'model' => $model,
                            'guest' => $guest,
                            'address' => $address,
                            'city' => $city,
                        ]);
                    }
                } else {
                    if ($model->dob) {
                        $model->dob = date('m/d/Y', $model->dob);
                    } else {
                        $model->dob = NULL;
                    }

                    $model->password = NULL;

                    $errors = $address->getErrors();
                    foreach($errors as $error) {
                        Yii::$app->getSession()->setFlash('success', [
                            'type' => Alert::TYPE_DANGER,
                            'duration' => 3000,
                            'icon' => 'fa fa-pencil',
                            'message' => $error[0],
                            'title' => Yii::t('app', 'Edit User'),
                        ]);
                    }

                    return $this->render('update', [
                        'model' => $model,
                        'guest' => $guest,
                        'address' => $address,
                        'city' => $city,
                    ]);
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        } else {
            if ($model->dob) {
                $model->dob = date('m/d/Y', $model->dob);
            } else {
                $model->dob = NULL;
            }
            return $this->render('update', [
                'model' => $model,
                'guest' => $guest,
                'address' => $address,
                'city' => $city,
            ]);
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
        $customer = $this->findModel($id);
        $customer->status = Customer::STATUS_INACTIVE;
        $customer->save();

        Yii::$app->getSession()->setFlash('success', [
            'type' => Alert::TYPE_SUCCESS,
            'duration' => 3000,
            'icon' => 'fa fa-trash-o',
            'message' => 'Customer has been deleted.',
            'title' => Yii::t('app', 'Delete User'),
        ]);


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
}
