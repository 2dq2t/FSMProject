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
        $searchModel = new CustomerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setPagination(['pageSize' => 7]);

        // validate if there is a editable input saved via AJAX
        if (Yii::$app->request->post('hasEditable')) {

            $customer_id = Yii::$app->request->post('editableKey');
            $model = Customer::findOne($customer_id);

            if(!$model) {
                // store a default json response as desired by editable
                $message = 'The User do not exist.';
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
                    $message = $model->validate();
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
        $model = new Customer(['scenario' => 'admincreate']);
        $guest = new Guest();
        $address = new Address();
        $district = new District();
        $ward = new Ward();
        $city = new City();

        // Load all file from post to model
        if ($model->load(Yii::$app->request->post())
            && $guest->load(Yii::$app->request->post())
            && $address->load(Yii::$app->request->post())) {
            // Begin transaction
            $transaction = Yii::$app->db->beginTransaction();
            try{
                // Save address info to database
                if($address->save()) {
                    $time = new \DateTime('now', new \DateTimeZone('Asia/Ho_Chi_Minh'));

                    // Set password and re_password to return create user page for error save user
//                    $password_return = $model->password;
//                    $re_password_return = $model->re_password;

                    // check password equal re_password and hash password
//                    if ($model->password === $model->re_password) {
//                        $model->setPassword($model->password);
//                        $model->re_password = $model->password;
//                    }

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

                    $model->created_at = $time->format('Y-m-d H:i:s');
                    $model->address_id = $address->id;
                    if ($model->save()) {
                        // directory to save image in local
                        $dir = Yii::getAlias('@frontend/web/uploads/users/avatar/' . $model->id);
                        FileHelper::createDirectory($dir);
                        // path to save database
//                        $path = 'uploads/users/avatar/' . $model->id . '/';
                        if ($avatar) {
                            $avatar->saveAs($dir . '/' . $model->avatar);
                        }

                        $guest->customer_id = $model->id;
                        $guest->save();

                        $transaction->commit();

                        Yii::$app->getSession()->setFlash('success', [
                            'type' => Alert::TYPE_SUCCESS,
                            'duration' => 3000,
                            'icon' => 'fa fa-plus',
                            'message' => 'User Record has been saved.',
                            'title' => 'Add User',
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
                        // get errors
                        $errors = $model->getErrors();
                        foreach($errors as $error) {
                            Yii::$app->getSession()->setFlash('success', [
                                'type' => Alert::TYPE_DANGER,
                                'duration' => 3000,
                                'icon' => 'fa fa-plus',
                                'message' => $error[0],
                                'title' => 'Add User',
                            ]);
                        }

                        return $this->render('create', [
                            'model' => $model,
                            'guest' => $guest,
                            'address' => $address,
                            'ward' => $ward,
                            'district' => $district,
                            'city' => $city,
                        ]);
                    }
                } else {
                    $errors = $address->getErrors();
                    foreach($errors as $error) {
                        Yii::$app->getSession()->setFlash('success', [
                            'type' => Alert::TYPE_DANGER,
                            'duration' => 3000,
                            'icon' => 'fa fa-plus',
                            'message' => $error[0],
                            'title' => 'Add User',
                        ]);
                    }

                    return $this->render('create', [
                        'model' => $model,
                        'guest' => $guest,
                        'address' => $address,
                        'ward' => $ward,
                        'district' => $district,
                        'city' => $city,
                    ]);
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'guest' => $guest,
                'address' => $address,
                'ward' => $ward,
                'district' => $district,
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
        $model->scenario = 'adminedit';
        $model->password = null;
        $guest = Guest::find()->where(['customer_id' => $id])->one();
        $address = Address::find()->where(['id' => $model->address_id])->one();
        $ward = Ward::find()->where(['id' => $address->ward_id])->one();
        $district = District::find()->where(['id' => $ward->district_id])->one();
        $city = City::find()->where(['id'=>$district->city_id])->one();

        // Load all file from post to model
        if ($model->load(Yii::$app->request->post())
            && $guest->load(Yii::$app->request->post())
            && $address->load(Yii::$app->request->post())) {

            // Begin transaction
            $transaction = Yii::$app->db->beginTransaction();
            try{
                // Save address info to database
                if($address->save()) {
                    $time = new \DateTime('now', new \DateTimeZone('Asia/Ho_Chi_Minh'));

                    // Set password and re_password to return update user page for error save user
//                    $password_return = $model->password;
//                    $re_password_return = $model->re_password;
//
//                     check password equal re_password and hash password
//                    if ($model->password === $model->re_password) {
//                        $model->setPassword($model->password);
//                        $model->re_password = $model->password;
//                    }
                    if ($model->password) {
                        $model->setPassword($model->password);
                    }

                    $avatar = UploadedFile::getInstance($model,'avatar');

                    if($avatar) {
                        $ext = $avatar->extension;

                        // generate a unique file name
                        $model->avatar = Yii::$app->security->generateRandomString().".{$ext}";

                        // the path to save file, you can set an uploadPath
                        // in Yii::$app->params (as used in example below)
//                        $path = Yii::$app->params['uploadPath'] . $model->avatar;
                    }

                    $model->updated_at = $time->format('Y-m-d H:i:s');
                    $model->address_id = $address->id;
                    if ($model->save()) {

                        // directory to save image in local
                        $dir = Yii::getAlias('@frontend/web/uploads/users/avatar/' . $model->id);
                        FileHelper::removeDirectory($dir);
                        FileHelper::createDirectory($dir);
                        // path to save database
//                        $path = 'frontend/uploads/users/avatar/' . $model->id . '/';
                        if ($avatar) {
                            $avatar->saveAs($dir . '/' . $model->avatar);
                        }

                        $guest->customer_id = $model->id;
                        $guest->save();

                        $transaction->commit();

                        Yii::$app->getSession()->setFlash('success', [
                            'type' => Alert::TYPE_SUCCESS,
                            'duration' => 3000,
                            'icon' => 'fa fa-pencil',
                            'message' => 'User Record has been edited.',
                            'title' => 'Edit User',
                        ]);

                        return $this->redirect(['index']);
                    } else {
                        // if save to user error return update page
//                        $model->password = $password_return;
//                        $model->re_password = $re_password_return;
                        // get errors
                        $errors = $model->getErrors();
                        foreach($errors as $error) {
                            Yii::$app->getSession()->setFlash('success', [
                                'type' => Alert::TYPE_DANGER,
                                'duration' => 3000,
                                'icon' => 'fa fa-pencil',
                                'message' => $error[0],
                                'title' => 'Edit User',
                            ]);
                        }

                        return $this->render('update', [
                            'model' => $model,
                            'guest' => $guest,
                            'address' => $address,
                            'ward' => $ward,
                            'district' => $district,
                            'city' => $city,
                        ]);
                    }
                } else {
                    $errors = $address->getErrors();
                    foreach($errors as $error) {
                        Yii::$app->getSession()->setFlash('success', [
                            'type' => Alert::TYPE_DANGER,
                            'duration' => 3000,
                            'icon' => 'fa fa-pencil',
                            'message' => $error[0],
                            'title' => 'Edit User',
                        ]);
                    }

                    return $this->render('update', [
                        'model' => $model,
                        'guest' => $guest,
                        'address' => $address,
                        'ward' => $ward,
                        'district' => $district,
                        'city' => $city,
                    ]);
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'guest' => $guest,
                'address' => $address,
                'ward' => $ward,
                'district' => $district,
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
        $guest = Guest::find()->where(['customer_id' => $id])->one();
        $guest->delete();
        $customer = $this->findModel($id);
        $customer->delete();

        $address = Address::find()->where(['id' => $customer->address_id])->one();
        $address->delete();

        $dir = Yii::getAlias('@frontend/web/uploads/users/avatar/' . $id);
        FileHelper::removeDirectory($dir);

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
}
