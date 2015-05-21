<?php

namespace backend\controllers;

use common\models\Address;
use common\models\City;
use common\models\District;
use common\models\Ward;
use kartik\alert\Alert;
use Yii;
use backend\models\Employee;
use backend\models\EmployeeSearch;
use yii\base\Exception;
use yii\helpers\FileHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * EmployeeController implements the CRUD actions for Employee model.
 */
class EmployeeController extends Controller
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
     * Lists all Employee models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EmployeeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setPagination(['pageSize' => 7]);

        // validate if there is a editable input saved via AJAX
        if (Yii::$app->request->post('hasEditable')) {

            $employee_id = Yii::$app->request->post('editableKey');
            $model = Employee::findOne($employee_id);

            if(!$model) {
                // store a default json response as desired by editable
                $message = Yii::t('app', 'The Employee do not exist.');
                echo $out = Json::encode(['output'=>'', 'message'=>$message]);
                return;
            }

            $post = [];
            $posted = current($_POST['Employee']);
            $post['Employee'] = $posted;

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
     * Displays a single Employee model.
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
     * Creates a new Employee model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Employee();
        $address = new Address();
        $city = new City();
        $model->scenario = 'adminCreate';

        if ($model->load(Yii::$app->request->post())
            && $address->load(Yii::$app->request->post())) {

            // Begin transaction
            $transaction = Yii::$app->db->beginTransaction();
            try{
                // Save address info to database
                if($address->save()) {
                    if ($model->password) {
                        $model->password = Yii::$app->security->generatePasswordHash($model->password);;
                    }

                    $image = UploadedFile::getInstance($model, 'image');

                    if($image) {
                        $ext = $image->extension;
                        // generate a unique file name
                        $model->image = Yii::$app->security->generateRandomString().".{$ext}";
                    }

                    $model->dob = strtotime($model->dob);
                    $model->start_date = strtotime($model->start_date);

                    $model->address_id = $address->id;

                    if ($model->save()) {
                        // directory to save image in local
                        $dir = Yii::getAlias('@backend/web/uploads/employees/image/' . $model->id);
                        FileHelper::createDirectory($dir);

                        if ($image) {
                            $image->saveAs($dir . '/' . $model->image);
                        }

                        $transaction->commit();

                        Yii::$app->getSession()->setFlash('success', [
                            'type' => Alert::TYPE_SUCCESS,
                            'duration' => 3000,
                            'icon' => 'fa fa-plus',
                            'message' => Yii::t('app', 'Employee Record has been saved.'),
                            'title' => Yii::t('app', 'Add Employee'),
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

                        if($model->start_date != '') {
                            $model->start_date = date('m/d/Y', $model->start_date);
                        }
                        // get errors
                        $errors = $model->getErrors();
                        foreach($errors as $error) {
                            Yii::$app->getSession()->setFlash('success', [
                                'type' => Alert::TYPE_DANGER,
                                'duration' => 3000,
                                'icon' => 'fa fa-plus',
                                'message' => $error[0],
                                'title' => Yii::t('app', 'Add Employee'),
                            ]);
                        }

                        $model->password = null;

                        return $this->render('create', [
                            'model' => $model,
                            'address' => $address,
                            'city' => $city,
                        ]);
                    }
                } else {
                    if ($model->dob != '') {
                        $model->dob = date('m/d/Y', $model->dob);
                    }

                    if($model->start_date != '') {
                        $model->start_date = date('m/d/Y', $model->start_date);
                    }
                    $errors = $address->getErrors();
                    foreach($errors as $error) {
                        Yii::$app->getSession()->setFlash('success', [
                            'type' => Alert::TYPE_DANGER,
                            'duration' => 3000,
                            'icon' => 'fa fa-plus',
                            'message' => $error[0],
                            'title' => Yii::t('app', 'Add Employee'),
                        ]);
                    }

                    $model->password = null;

                    return $this->render('create', [
                        'model' => $model,
                        'address' => $address,
                        'city' => $city,
                    ]);
                }
            } catch (Exception $e) {
                $transaction->rollBack();
                if ($model->dob != '') {
                    $model->dob = date('m/d/Y', $model->dob);
                }

                if($model->start_date != '') {
                    $model->start_date = date('m/d/Y', $model->start_date);
                }
                $errors = $address->getErrors();
                foreach($errors as $error) {
                    Yii::$app->getSession()->setFlash('error', [
                        'type' => Alert::TYPE_DANGER,
                        'duration' => 3000,
                        'icon' => 'fa fa-plus',
                        'message' => $error[0],
                        'title' => Yii::t('app', 'Add Employee'),
                    ]);
                }

                $model->password = null;

                return $this->render('create', [
                    'model' => $model,
                    'address' => $address,
                    'city' => $city,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'address' => $address,
                'city' => $city,
            ]);
        }
    }

    /**
     * Updates an existing Employee model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $address = Address::find()->where(['id' => $model->address_id])->one();
        $district = District::find()->where(['id' => $address->district_id])->one();
        $city = City::find()->where(['id'=>$district->city_id])->one();
        $model->scenario = 'adminEdit';

        $model->password = null;
        $model->dob = date('m/d/Y', $model->dob);
        $model->start_date = date('m/d/Y', $model->start_date);

        if ($model->load(Yii::$app->request->post())
            && $address->load(Yii::$app->request->post())) {

            if (Yii::$app->request->post('Employee')['status'] === '1') {
                $model->status = 1;
            } else {
                $model->status = 0;
            }

            if(Yii::$app->request->post('Employee')['password'] === '') {
                $model->password = $model->oldAttributes['password'];
            }

            if(!UploadedFile::getInstance($model, 'image')) {
                $model->image = $model->oldAttributes['image'];
            }

            $model->dob = strtotime($model->dob);
            $model->start_date = strtotime($model->start_date);

            // Begin transaction
            $transaction = Yii::$app->db->beginTransaction();
            try{
                // Save address info to database
                if($address->save()) {
                    if ($model->password) {
                        $model->password = Yii::$app->security->generatePasswordHash($model->password);;
                    }

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
                            'type' => Alert::TYPE_SUCCESS,
                            'duration' => 3000,
                            'icon' => 'fa fa-plus',
                            'message' => Yii::t('app', 'Employee Record has been saved.'),
                            'title' => Yii::t('app', 'Edit Employee'),
                        ]);

                        switch (Yii::$app->request->post('action', 'save')) {
                            case 'next':
                                return $this->redirect(['create']);
                            default:
                                return $this->redirect(['index']);
                        }
                    }
                }
            } catch (Exception $e) {
                $transaction->rollBack();

                Yii::$app->getSession()->setFlash('error', [
                    'type' => Alert::TYPE_DANGER,
                    'duration' => 3000,
                    'icon' => 'fa fa-plus',
                    'message' => $e->getMessage(),
                    'title' => Yii::t('app', 'Edit Employee'),
                ]);

                $model->password = null;

                return $this->render('update', [
                    'model' => $model,
                    'address' => $address,
                    'city' => $city,
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'address' => $address,
                'city' => $city,
            ]);
        }
    }

    /**
     * Deletes an existing Employee model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {

        if ($this->findModel($id)->email !== Yii::$app->user->identity->email) {
            $employee = $this->findModel($id);
            $employee->status = Employee::STATUS_INACTIVE;
            $employee->save();

            Yii::$app->getSession()->setFlash('success', [
                'type' => Alert::TYPE_SUCCESS,
                'duration' => 3000,
                'icon' => 'fa fa-plus',
                'message' => Yii::t('app', 'Employee has been deleted.'),
                'title' => Yii::t('app', 'Delete Employee'),
            ]);
        } else {
            Yii::$app->getSession()->setFlash('error', [
                'type' => Alert::TYPE_DANGER,
                'duration' => 3000,
                'icon' => 'fa fa-plus',
                'message' => Yii::t('app', 'You can\'t delete yourself.'),
                'title' => Yii::t('app', 'Delete Employee'),
            ]);
        }

        return $this->redirect(['index']);
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
