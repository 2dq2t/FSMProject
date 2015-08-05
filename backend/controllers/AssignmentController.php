<?php

namespace backend\controllers;

use backend\components\Logger;
use backend\models\Employee;
use Yii;
//use mdm\admin\models\searchs\Assignment as AssignmentSearch;
use backend\models\AssignmentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;


/**
 * AssignmentController implements the CRUD actions for Assignment model.
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class AssignmentController extends Controller
{
    public $userClassName;
    public $idField = 'id';
    public $usernameField = 'email';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->userClassName === null) {
            $this->userClassName = Yii::$app->getUser()->identityClass;
            $this->userClassName = $this->userClassName ? : 'backend\models\Employee';
        }
    }

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
        ];
    }

    /**
     * Lists all Assignment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AssignmentSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->getQueryParams(), $this->userClassName, $this->usernameField);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'idField' => $this->idField,
            'usernameField' => $this->usernameField,
        ]);
    }

    /**
     * Displays a single Assignment model.
     * @param  integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model,
            'idField' => $this->idField,
            'usernameField' => $this->usernameField,
        ]);
    }

    /**
     * Assign or revoke assignment to user
     * @param  integer $id
     * @param  string  $action
     * @return mixed
     */
    public function actionAssign()
    {
        $post = Yii::$app->request->post();
        $id = $post['id'];
        $action = $post['action'];
        $roles = $post['roles'];
        $manager = Yii::$app->authManager;
        $error = [];
        if ($action == 'assign') {
            foreach ($roles as $name) {
                try {
                    $item = $manager->getRole($name);
//                    $item = $item ? : $manager->getPermission($name);
                    $manager->assign($item, $id);
                    Logger::log(Logger::INFO, Yii::t('app', 'Assign role to user success. User assign: ') . $id, Yii::$app->user->identity->email);
                } catch (\Exception $exc) {
                    $error[] = $exc->getMessage();
                    Logger::log(Logger::ERROR, Yii::t('app', 'Could not assign role: ') . $exc->getMessage(), Yii::$app->user->identity->email);
                }
            }
        } else {
            foreach ($roles as $name) {
                try {
                    $item = $manager->getRole($name);
//                    $item = $item ? : $manager->getPermission($name);
                    $manager->revoke($item, $id);
                    Logger::log(Logger::INFO, Yii::t('app', 'Revoke role success to user: ') . $id, Yii::$app->user->identity->email);
                } catch (\Exception $exc) {
                    $error[] = $exc->getMessage();
                    Logger::log(Logger::ERROR, Yii::t('app', 'Could not revoke role: ') . $exc->getMessage(), Yii::$app->user->identity->email);
                }
            }
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return[
            'type' => 'S',
            'errors' => $error,
        ];
    }

    /**
     * Search roles of user
     * @param  integer $id
     * @param  string  $target
     * @param  string  $term
     * @return string
     */
    public function actionSearch($id, $target, $term = '')
    {
        Yii::$app->response->format = 'json';
        $authManager = Yii::$app->authManager;
        $roles = $authManager->getRoles();
//        $permissions = $authManager->getPermissions();

        $avaliable = [];
        $assigned = [];
        foreach ($authManager->getAssignments($id) as $assigment) {
            if (isset($roles[$assigment->roleName])) {
                if (empty($term) || strpos($assigment->roleName, $term) !== false) {
                    $assigned['Roles'][$assigment->roleName] = $assigment->roleName;
                }
                unset($roles[$assigment->roleName]);
            }
//            elseif (isset($permissions[$assigment->roleName]) && $assigment->roleName[0] != '/') {
//                if (empty($term) || strpos($assigment->roleName, $term) !== false) {
//                    $assigned['Permissions'][$assigment->roleName] = $assigment->roleName;
//                }
//                unset($permissions[$assigment->roleName]);
//            }
        }

        if ($target == 'avaliable') {
            if (count($roles)) {
                foreach ($roles as $role) {
                    if (empty($term) || strpos($role->name, $term) !== false) {
                        $avaliable['Roles'][$role->name] = $role->name;
                    }
                }
            }
//            if (count($permissions)) {
//                foreach ($permissions as $role) {
//                    if ($role->name[0] != '/' && (empty($term) || strpos($role->name, $term) !== false)) {
//                        $avaliable['Permissions'][$role->name] = $role->name;
//                    }
//                }
//            }
            return $avaliable;
        } else {
            return $assigned;
        }
    }

    /**
     * Finds the Assignment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param  integer $id
     * @return Employee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $class = $this->userClassName;
        if (($model = $class::findIdentity($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}