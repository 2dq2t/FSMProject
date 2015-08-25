<?php

namespace backend\controllers;

use backend\components\Logger;
use backend\components\ParserDateTime;
use common\models\City;
use common\models\District;
use common\models\Guest;
use common\models\Address;
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
            'access' => [
                'class' => YII_DEBUG ?  \yii\base\ActionFilter::className() : \backend\components\AccessControl::className()
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

                    Logger::log(Logger::INFO, Yii::t('app', 'Update Customer'), Yii::$app->user->identity->email, $model->oldAttributes, $model->attributes);

                } else {
                    $message = $model->errors;
                    Logger::log(Logger::ERROR, Yii::t('app', 'Update Customer') . ' ' . current($model->getFirstErrors()), Yii::$app->user->identity->email);
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
//    public function actionView($id)
//    {
//        return $this->render('view', [
//            'model' => $this->findModel($id),
//        ]);
//    }

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
                    }

                    $model->created_at = ParserDateTime::getTimeStamp();
                    $model->dob = ParserDateTime::parseToTimestamp($model->dob);
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
                            'type' => 'success',
                            'duration' => 3000,
                            'icon' => 'fa fa-plus',
                            'message' => Yii::t('app', 'Customer_Add_Success_Msg.'),
                            'title' => Yii::t('app', 'Create Customer'),
                        ]);

                        Logger::log(Logger::INFO, Yii::t('app', 'Create customer address success'), Yii::$app->user->identity->email);
                        Logger::log(Logger::INFO, Yii::t('app', 'Create customer guest success'), Yii::$app->user->identity->email);
                        Logger::log(Logger::INFO, Yii::t('app', 'Create customer success'), Yii::$app->user->identity->email);

                        switch (Yii::$app->request->post('action', 'save')) {
                            case 'next':
                                return $this->redirect(['create']);
                            default:
                                return $this->redirect(['index']);
                        }
                    } else {
                        $transaction->rollBack();

                        if ($model->dob != '') {
                            $model->dob = date('d/m/Y', $model->dob);
                        }

                        if($model->created_at != '') {
                            $model->created_at = date('d/m/Y', $model->created_at);
                        }

                        Yii::$app->getSession()->setFlash('error', [
                            'type' => 'error',
                            'duration' => 0,
                            'icon' => 'fa fa-plus',
                            'message' => current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app','Customer_Add_Error_Msg'),
                            'title' => Yii::t('app', 'Create Customer'),
                        ]);

                        $model->password = null;

                        Logger::log(Logger::ERROR, Yii::t('app', 'Create Customer error: ') . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app','Customer_Add_Error_Msg'), Yii::$app->user->identity->email);

                        return $this->render('create', [
                            'model' => $model,
                            'guest' => $guest,
                            'address' => $address,
                            'city' => $city,
                        ]);
                    }
                } else {
                    $transaction->rollBack();

                    Yii::$app->getSession()->setFlash('error', [
                        'type' => 'error',
                        'duration' => 0,
                        'icon' => 'fa fa-plus',
                        'message' => current($guest->getFirstErrors()) || current($address->getFirstErrors())  ? current($guest->getFirstErrors()) || current($address->getFirstErrors()) : Yii::t('app','Customer_Add_Error_Msg'),
                        'title' => Yii::t('app', 'Create Customer'),
                    ]);

                    $model->password = null;

                    Logger::log(Logger::ERROR, Yii::t('app', 'Create Customer error: ') . current($guest->getFirstErrors()) || current($address->getFirstErrors())  ? current($guest->getFirstErrors()) || current($address->getFirstErrors()) : Yii::t('app','Customer_Add_Error_Msg'), Yii::$app->user->identity->email);

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
                    $model->dob = date('d/m/Y', $model->dob);
                }

                if($model->created_at != '') {
                    $model->created_at = date('d/m/Y', $model->created_at);
                }

                Yii::$app->getSession()->setFlash('error', [
                    'type' => 'error',
                    'duration' => 0,
                    'icon' => 'fa fa-plus',
                    'message' => $e->getMessage() ? $e->getMessage() : Yii::t('app', 'Customer_Add_Error_Msg'),
                    'title' => Yii::t('app', 'Create Customer'),
                ]);

                $model->password = null;

                Logger::log(Logger::ERROR, Yii::t('app', 'Create customer error: ') . $e->getMessage() ? $e->getMessage() : Yii::t('app', 'Customer_Add_Error_Msg'), Yii::$app->user->identity->email);

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
        if (trim($model->address_id) !== '') {
            $address = Address::find()->where(['id' => $model->address_id])->one();
            $district = District::find()->where(['id' => $address->district_id])->one();
            $city = City::find()->where(['id'=>$district->city_id])->one();
        } else {
            $address = new Address();
            $city = new City();
        }

        // Load all file from post to model
        if ($model->load(Yii::$app->request->post())
            && $guest->load(Yii::$app->request->post())
            && $address->load(Yii::$app->request->post())) {

            $oldGuest = $guest->oldAttributes;
            $oldAddress = $address->oldAttributes;
            $oldModel = $model->oldAttributes;
            // Begin transaction
            $transaction = Yii::$app->db->beginTransaction();
            try{
                // Save address info to database
                if($address->save() && $guest->save()) {

                    $avatar = UploadedFile::getInstance($model,'avatar');
                    if($avatar) {
                        $ext = $avatar->extension;
                        // generate a unique file name
                        $model->avatar = Yii::$app->security->generateRandomString().".{$ext}";
                    } else {
                        $model->avatar = $model->oldAttributes['avatar'];
                    }

                    $model->dob = ParserDateTime::parseToTimestamp(Yii::$app->request->post('Customer')['dob']);
                    if (Yii::$app->request->post('Customer')['password'] === '') {
                        $model->password = $model->oldAttributes['password'];
                    } else {
                        $model->setPassword(Yii::$app->request->post('Customer')['password']);
                    }

                    $model->gender = Yii::$app->request->post('Customer')['gender'] ? Yii::$app->request->post('Customer')['gender'] : $model->oldAttributes['gender'];
                    $model->address_id = $address->id;
                    $model->updated_at = ParserDateTime::getTimeStamp();

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
                            'type' => 'success',
                            'duration' => 3000,
                            'icon' => 'fa fa-pencil',
                            'message' => Yii::t('app', 'Customer_Update_Success_Msg.'),
                            'title' => Yii::t('app', 'Update Customer'),
                        ]);

                        Logger::log(Logger::INFO, Yii::t('app', 'Update customer address success'), Yii::$app->user->identity->email, $oldAddress, $address->attributes);
                        Logger::log(Logger::INFO, Yii::t('app', 'Update customer guest success'), Yii::$app->user->identity->email, $oldGuest, $guest->attributes);
                        Logger::log(Logger::INFO, Yii::t('app', 'Update customer success'), Yii::$app->user->identity->email, $oldModel, $model->attributes);

                        return $this->redirect(['index']);
                    } else {
                        $transaction->rollBack();

                        if ($model->dob) {
                            $model->dob = date('d/m/Y', $model->dob);
                        } else {
                            $model->dob = NULL;
                        }

                        $model->password = NULL;

                        Yii::$app->getSession()->setFlash('error', [
                            'type' => 'error',
                            'duration' => 0,
                            'icon' => 'fa fa-pencil',
                            'message' => current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Customer_Update_Error_Msg'),
                            'title' => Yii::t('app', 'Update Customer'),
                        ]);

                        Logger::log(Logger::ERROR, Yii::t('app', 'Update customer error: ') . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Update customer error: '), Yii::$app->user->identity->email);

                        return $this->render('update', [
                            'model' => $model,
                            'guest' => $guest,
                            'address' => $address,
                            'city' => $city,
                        ]);
                    }
                } else {
                    $transaction->rollBack();

                    if ($model->dob) {
                        $model->dob = date('d/m/Y', $model->dob);
                    } else {
                        $model->dob = NULL;
                    }

                    Yii::$app->getSession()->setFlash('error', [
                        'type' => 'error',
                        'duration' => 0,
                        'icon' => 'fa fa-pencil',
                        'message' => current($guest->getFirstErrors()) || current($address->getFirstErrors()) ? current($guest->getFirstErrors()) || current($address->getFirstErrors()) : Yii::t('app', 'Customer_Update_Error_Msg'),
                        'title' => Yii::t('app', 'Update Customer'),
                    ]);

                    Logger::log(Logger::ERROR, Yii::t('app', 'Update customer error: ') . current($guest->getFirstErrors()) || current($address->getFirstErrors()) ? current($guest->getFirstErrors()) || current($address->getFirstErrors()) : Yii::t('app', 'Update customer error: '), Yii::$app->user->identity->email);

                    return $this->render('update', [
                        'model' => $model,
                        'guest' => $guest,
                        'address' => $address,
                        'city' => $city,
                    ]);
                }
            } catch (Exception $e) {
                $transaction->rollBack();

                if ($model->dob) {
                    $model->dob = date('d/m/Y', $model->dob);
                } else {
                    $model->dob = NULL;
                }

                $model->password = NULL;

                Yii::$app->getSession()->setFlash('error', [
                    'type' => 'error',
                    'duration' => 0,
                    'icon' => 'fa fa-pencil',
                    'message' => $e->getMessage() ? $e->getMessage() : Yii::t('app', 'Customer_Update_Error_Msg'),
                    'title' => Yii::t('app', 'Update Customer'),
                ]);

                Logger::log(Logger::ERROR, Yii::t('app', 'Update customer error: '). $e->getMessage() ? $e->getMessage() : Yii::t('app', 'Update Customer error'), Yii::$app->user->identity->email);

                return $this->render('update', [
                    'model' => $model,
                    'guest' => $guest,
                    'address' => $address,
                    'city' => $city,
                ]);
            }
        } else {
            if ($model->dob) {
                $model->dob = date('d/m/Y', $model->dob);
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
//     */
//    public function actionDelete($id)
//    {
//        $customer = $this->findModel($id);
//        $customer->status = Customer::STATUS_INACTIVE;
//        if ($customer->save()) {
//            Yii::$app->getSession()->setFlash('success', [
//                'type' => 'success',
//                'duration' => 3000,
//                'icon' => 'fa fa-trash-o',
//                'message' => Yii::t('app','Customer_Delete_Success_Msg'),
//                'title' => Yii::t('app', 'Delete Customer'),
//            ]);
//
//            Logger::log(Logger::INFO, Yii::t('app', 'Delete cutomer success'), Yii::$app->user->identity->email);
//
//        } else {
//            Yii::$app->getSession()->setFlash('error', [
//                'type' => 'error',
//                'duration' => 0,
//                'icon' => 'fa fa-trash-o',
//                'message' => current($customer->getFirstErrors()) ? current($customer->getFirstErrors()) : Yii::t('app','Could not delete this customer. Please try again.'),
//                'title' => Yii::t('app', 'Delete Customer'),
//            ]);
//
//            Logger::log(Logger::ERROR, Yii::t('app', 'Delete customer error: ') . current($customer->getFirstErrors()) ? current($customer->getFirstErrors()) : Yii::t('app', 'Could not delete customer'),Yii::$app->user->identity->email);
//        }
//
//
//        return $this->redirect(['index']);
//    }

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
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /* @param $id integer  */
    public function actionGetdistrict($id = 0) {
        if ($id > 0) {
            $countDistrict= District::find()
                ->where(['city_id' => $id])
                ->count();

            $districts = District::find()
                ->where(['city_id' => $id])
                ->orderBy('name')
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
}
