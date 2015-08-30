<?php
/**
 * Created by PhpStorm.
 * User: TuDA
 * Date: 8/6/2015
 * Time: 9:45 AM
 */

namespace frontend\controllers;

use backend\models\OrderView;
use common\models\Guest;
use common\models\Customer;
use common\models\Address;
use common\models\District;
use common\models\City;
use common\models\Order;
use common\models\Voucher;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use yii\db\Query;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use Yii;
use common\models\LoginForm;
use yii\base\Exception;
use yii\helpers\JSon;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use kartik\alert\Alert;
use yii\web\NotFoundHttpException;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use backend\components\ParserDateTime;

class AccountController extends Controller{

    /**
     * @inheritdoc
     */
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
                'only' => ['update', 'manageacc', 'changepass'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['update', 'manageacc', 'changepass'],
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
        Customer Login
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goHome();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
        Customer Register
     */
    public function actionRegister()
    {

        $modelCustomer = new Customer();
        $modelCustomer->scenario = 'addCustomer';
        $modelGuest = new Guest();

        if ($modelCustomer->load(Yii::$app->request->post())
            && $modelGuest->load(Yii::$app->request->post())
        ) {

            //Begin transaction
            $transaction = Yii::$app->db->beginTransaction();
            try {
                //save guest to database
                if ($modelGuest->save()) {
                    $modelCustomer->guest_id = $modelGuest->id;
                    $modelCustomer->setPassword($modelCustomer->password);
//                    $modelCustomer->re_password = $modelCustomer->password;
                    $modelCustomer->setRePassword($modelCustomer->password);
                    $modelCustomer->created_at = ParserDateTime::getTimeStamp();

                    if ($modelCustomer->save()) {
                        $transaction->commit();
                        if (Yii::$app->getUser()->login($modelCustomer)) {
                            $this->goHome();
                        }
                    } else {
                        return $this->render('register', [
                            'modelCustomer' => $modelCustomer,
                            'modelGuest' => $modelGuest,
                        ]);
                    }
                } else {
                    return $this->render('register', [
                        'modelCustomer' => $modelCustomer,
                        'modelGuest' => $modelGuest,
                    ]);
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        } else {
            return $this->render('register', [
                'modelCustomer' => $modelCustomer,
                'modelGuest' => $modelGuest,
            ]);
        }

    }

    /**
        Customer Logout
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionManageacc($id)
    {

        if (Yii::$app->user->id != $id) {
            return $this->redirect('index.php?r=account/manageacc&id=' . Yii::$app->user->id . '');
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
            return $this->redirect('index.php?r=account/update&id=' . Yii::$app->user->id . '');
        }

        $modelCustomer = $this->findModel($id);
        $modelGuest = Guest::find()->where(['id' => $modelCustomer->guest_id])->one();

        if ($modelCustomer->load(Yii::$app->request->post())
            && $modelGuest->load(Yii::$app->request->post())
        ) {

            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($modelGuest->save()) {

                    $avatar = UploadedFile::getInstance($modelCustomer, 'avatar');

                    if ($avatar) {
                        $ext = $avatar->extension;
                        // generate a unique file name
                        $modelCustomer->avatar = Yii::$app->security->generateRandomString() . ".{$ext}";
                    } else {
                        $modelCustomer->avatar = $modelCustomer->oldAttributes['avatar'];
                    }

                    $modelCustomer->guest_id = $modelGuest->id;
                    $modelCustomer->dob = ParserDateTime::parseToTimestamp(Yii::$app->request->post('Customer')['dob']);
                    $modelCustomer->gender = Yii::$app->request->post('Customer')['gender'] ? Yii::$app->request->post('Customer')['gender'] : $modelCustomer->oldAttributes['gender'];;
                    $modelCustomer->updated_at = time();
                    if ($modelCustomer->save()) {
                        if ($avatar) {
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
                            'message' => Yii::t('app', 'UpdateProfileMsg01'),
                            'title' => Yii::t('app', 'ChangeAccInfoLabel'),
                        ]);

                        return $this->redirect(['update', 'id' => $modelCustomer->id]);

                    } else {

                        if ($modelCustomer->dob) {
                            $modelCustomer->dob = date('d/m/Y', $modelCustomer->dob);
                        } else {
                            $modelCustomer->dob = NULL;
                        }

                        $errors = $modelCustomer->getErrors();
                        foreach ($errors as $error) {
                            Yii::$app->getSession()->setFlash('failed', [
                                'type' => Alert::TYPE_DANGER,
                                'duration' => 3000,
                                'icon' => 'fa fa-plus',
                                'message' => $error[0],
                                'title' => Yii::t('app', 'ChangeAccInfoLabel'),
                            ]);
                        }

                        return $this->render('changeinfo', [
                            'modelCustomer' => $modelCustomer,
                            'modelGuest' => $modelGuest,
                        ]);
                    }
                } else {

                    if ($modelCustomer->dob) {
                        $modelCustomer->dob = date('d/m/Y', $modelCustomer->dob);
                    } else {
                        $modelCustomer->dob = NULL;
                    }

                    $errors = $modelCustomer->getErrors();
                    foreach ($errors as $error) {
                        Yii::$app->getSession()->setFlash('failed', [
                            'type' => Alert::TYPE_DANGER,
                            'duration' => 3000,
                            'icon' => 'fa fa-plus',
                            'message' => $error[0],
                            'title' => Yii::t('app', 'ChangeAccInfoLabel'),
                        ]);
                    }

                    return $this->render('changeinfo', [
                        'modelCustomer' => $modelCustomer,
                        'modelGuest' => $modelGuest,
                    ]);
                }
            } catch (Exception $e) {
                $transaction->rollBack();

                if ($modelCustomer->dob) {
                    $modelCustomer->dob = date('d/m/Y', $modelCustomer->dob);
                } else {
                    $modelCustomer->dob = NULL;
                }

                $errors = $modelCustomer->getErrors();
                foreach ($errors as $error) {
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => Alert::TYPE_DANGER,
                        'duration' => 3000,
                        'icon' => 'fa fa-plus',
                        'message' => $error[0],
                        'title' => Yii::t('app', 'ChangeAccInfoLabel'),
                    ]);
                }
            }
        } else {

            if ($modelCustomer->dob) {
                $modelCustomer->dob = date('d/m/Y', $modelCustomer->dob);
            } else {
                $modelCustomer->dob = NULL;
            }
            return $this->render('changeinfo', [
                'modelCustomer' => $modelCustomer,
                'modelGuest' => $modelGuest,
            ]);
        }
    }

    public function actionChangepass($id)
    {

        if (Yii::$app->user->id != $id) {
            return $this->redirect('index.php?r=account/changepass&id=' . Yii::$app->user->id . '');
        }

        $modelCustomer = $this->findModel($id);
        $modelCustomer->scenario = 'changePassword';
        if ($modelCustomer->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
//                $modelCustomer->setPassword($modelCustomer->new_password);
                $modelCustomer->setPassword($modelCustomer->getNewPassword());
                if ($modelCustomer->save()) {
                    $transaction->commit();

                    Yii::$app->getSession()->setFlash('successful', [
                        'type' => Alert::TYPE_SUCCESS,
                        'duration' => 3000,
                        'icon' => 'fa fa-plus',
                        'message' => Yii::t('app', 'ChangePasswordMsg01'),
                        'title' => Yii::t('app', 'ChangePassInfoLabel'),
                    ]);

                    return $this->redirect(['update', 'id' => $modelCustomer->id]);
                } else {

                    $errors = $modelCustomer->getErrors();
                    foreach ($errors as $error) {
                        Yii::$app->getSession()->setFlash('failed', [
                            'type' => Alert::TYPE_DANGER,
                            'duration' => 3000,
                            'icon' => 'fa fa-plus',
                            'message' => $error[0],
                            'title' => Yii::t('app', 'ChangePassInfoLabel'),
                        ]);
                    }

                    return $this->render('changepass', [
                        'modelCustomer' => $modelCustomer,
                    ]);
                }
            } catch (Exception $e) {
                $errors = $modelCustomer->getErrors();
                foreach ($errors as $error) {
                    Yii::$app->getSession()->setFlash('failed', [
                        'type' => Alert::TYPE_DANGER,
                        'duration' => 3000,
                        'icon' => 'fa fa-plus',
                        'message' => $error[0],
                        'title' => Yii::t('app', 'ChangePassInfoLabel'),
                    ]);
                }

                $transaction->rollBack();
            }
        }

        return $this->render('changepass', [
            'modelCustomer' => $modelCustomer,
        ]);
    }

    public function actionChangeaddress($id)
    {

        $modelCustomer = $this->findModel($id);
        $modelUpdateAddress = Address::find()->where(['id' => $modelCustomer->address_id])->one();

        //When address_id of Customer equal null => add new address
        if ($modelUpdateAddress == null) {
            $modelAddress = new Address();
            $modelCity = new City();
            $modelDistrict = new District();

            if ($modelAddress->load(Yii::$app->request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($modelAddress->save()) {
                        $modelCustomer->address_id = $modelAddress->id;
                        $modelCustomer->save();
                        $transaction->commit();

                        Yii::$app->getSession()->setFlash('successful', [
                            'type' => Alert::TYPE_SUCCESS,
                            'duration' => 3000,
                            'icon' => 'fa fa-plus',
                            'message' => Yii::t('app', 'ChangeAddressMsg01'),
                            'title' => Yii::t('app', 'ChangeAddressInfoLabel'),
                        ]);
                        return $this->redirect(['changeaddress', 'id' => $modelCustomer->id]);
                    }
                } catch (Exception $e) {
                    $errors = $modelAddress->getErrors();
                    foreach ($errors as $error) {
                        Yii::$app->getSession()->setFlash('failed', [
                            'type' => Alert::TYPE_DANGER,
                            'duration' => 3000,
                            'icon' => 'fa fa-plus',
                            'message' => $error[0],
                            'title' => Yii::t('app', 'ChangeAddressInfoLabel'),
                        ]);
                    }

                    $transaction->rollBack();
                }
            } else {
                return $this->render('changeaddress', [
                    'modelAddress' => $modelAddress,
                    'modelCity' => $modelCity,
                    'modelDistrict' => $modelDistrict,
                ]);
            }
        } else {
            $modelUpdateDistrict = District::find()->where(['id' => $modelUpdateAddress->district_id])->one();
            $modelUpdateCity = City::find()->where(['id' => $modelUpdateDistrict->city_id])->one();

            if ($modelUpdateAddress->load(Yii::$app->request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($modelUpdateAddress->save()) {
                        $modelCustomer->address_id = $modelUpdateAddress->id;
                        $transaction->commit();

                        Yii::$app->getSession()->setFlash('successful', [
                            'type' => Alert::TYPE_SUCCESS,
                            'duration' => 3000,
                            'icon' => 'fa fa-plus',
                            'message' => Yii::t('app', 'ChangeAddressMsg01'),
                            'title' => Yii::t('app', 'ChangeAddressInfoLabel'),
                        ]);
                        return $this->redirect(['changeaddress', 'id' => $modelCustomer->id]);
                    }
                } catch (Exception $e) {
                    $errors = $modelUpdateAddress->getErrors();
                    foreach ($errors as $error) {
                        Yii::$app->getSession()->setFlash('failed', [
                            'type' => Alert::TYPE_DANGER,
                            'duration' => 3000,
                            'icon' => 'fa fa-plus',
                            'message' => $error[0],
                            'title' => Yii::t('app', 'ChangeAddressInfoLabel'),
                        ]);
                    }

                    $transaction->rollBack();
                }
            } else {
                return $this->render('changeaddress', [
                    'modelUpdateAddress' => $modelUpdateAddress,
                    'modelUpdateCity' => $modelUpdateCity,
                    'modelUpdateDistrict' => $modelUpdateDistrict,
                ]);
            }
        }


    }

    public function actionGetdistrict($id = null)
    {
        if (isset($id)) {
            $countDistrict = District::find()
                ->where(['city_id' => $id])
                ->count();

            $districts = District::find()
                ->where(['city_id' => $id])
                ->orderBy('name')
                ->all();

            if ($countDistrict > 0) {
                foreach ($districts as $district) {
                    echo "<option value='" . $district->id . "'>" . $district->name . "</option>";
                }
            } else {
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
            echo Json::encode(['output' => '', 'selected' => '']);
        }
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('successful', [
                    'type' => Alert::TYPE_SUCCESS,
                    'duration' => 3000,
                    'icon' => 'fa fa-plus',
                    'message' => Yii::t('app', 'RequestPasswordResetMsg01'),
                    'title' => Yii::t('app', 'ForgottenPasswordLabel'),
                ]);

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('failed', [
                    'type' => Alert::TYPE_DANGER,
                    'duration' => 3000,
                    'icon' => 'fa fa-plus',
                    'message' => Yii::t('app', 'RequestPasswordResetMsg02'),
                    'title' => Yii::t('app', 'ForgottenPasswordLabel'),
                ]);
                return $this->goHome();
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->getSession()->setFlash('successful', [
                'type' => Alert::TYPE_SUCCESS,
                'duration' => 3000,
                'icon' => 'fa fa-plus',
                'message' => Yii::t('app', 'ResetPasswordMsg01'),
                'title' => Yii::t('app', 'ChangePassInfoLabel'),
            ]);

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
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
    public function actionGetOrderHistory(){
        if(Yii::$app->user->isGuest){
            return $this->redirect('index.php?r=account/register');
        }
        else{
            $customer = Customer::find()->select(['guest_id'])->where(['id'=>Yii::$app->user->identity->getId()])->one();
            $order = Order::find()->select(['id'])->where(['guest_id'=>$customer['guest_id']])->all();
            $order_id = array();
            foreach($order as $id){
                array_push($order_id,$id['id']);
            }
            $query = OrderView::find()->where(['IN','order_id',$order_id]);
            $dataProvider = new \yii\data\ActiveDataProvider([
                'query'=>$query,
                'pagination' => [
                    'pagesize' => 7
                ]
            ]);

            return $this->render('getOrderHistory', [
                'dataProvider' => $dataProvider,
            ]);
        }
    }
}