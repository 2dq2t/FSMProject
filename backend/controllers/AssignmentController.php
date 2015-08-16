<?php

namespace backend\controllers;

use backend\components\Logger;
use backend\models\Employee;
use backend\models\EmployeeSearch;
use Yii;
//use mdm\admin\models\searchs\Assignment as AssignmentSearch;
use backend\models\AssignmentSearch;
use yii\rbac\Item;
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
                'class' => YII_DEBUG ?  ActionFilter::className() : \backend\components\AccessControl::className()
            ],
        ];
    }

    /**
     * Lists all Assignment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EmployeeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }

    /**
     * Updates roles an existing Employee model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @var Employee $model
     * @return mixed
     */
    public function actionUpdate($id)
    {
        /* @var $model Employee*/
        $model = Employee::findIdentity($id);

        $items = [
            'Roles' => []
        ];

        $authManager = Yii::$app->authManager;
        foreach ($authManager->getRoles() as $name => $role) {
            if (empty($term) or strpos($name, $term) !== false) {
                $items['Roles'][$name] = $name;
            }
        }

        $model->setAssignments(array_keys(Yii::$app->getAuthManager()->getAssignments($id)));

        if($model->load(Yii::$app->request->post())) {
            if (!empty($model->getAssignments())) {
                $errors = [];
                $assignments = $model->getAssignments();
                $transaction = Yii::$app->db->beginTransaction();
                foreach (Yii::$app->getAuthManager()->getAssignments($id) as $assignment) {
                    $key = array_search($assignment->roleName, $assignments);
                    if ($key === false) {
                        \Yii::$app->authManager->revoke(new Item(['name' => $assignment->roleName]), $id);
                    } else {
                        unset($assignments[$key]);
                    }
                }

                foreach ($assignments as $assignment) {
                    try {
                        \Yii::$app->authManager->assign(new Item(['name' => $assignment]), $id);
                    } catch (\Exception $e) {
                        $errors[] = Yii::t('app', 'Cannot assign {assignment} to user', ['{assignment}' => $assignment]);
                    }
                }

                if (!empty($errors)) {
                    $transaction->rollBack();
                    Yii::$app->getSession()->setFlash('error', [
                        'type' => 'error',
                        'duration' => 0,
                        'icon' => 'fa fa-plus',
                        'message' => $errors[0],
                        'title' => Yii::t('app', 'Assignment to employee')
                    ]);

                    Logger::log(Logger::ERROR, Yii::t('app', 'Assignment to employee error: ') . $errors[0], Yii::$app->user->identity->email);

                    return $this->render('update', [
                        'model' => $model,
                        'items' => $items
                    ]);
                } else {
                    $transaction->commit();
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'success',
                        'duration' => 3000,
                        'icon' => 'fa fa-plus',
                        'message' => Yii::t('app', 'Assignment success.'),
                        'title' => Yii::t('app', 'Assignment to employee')
                    ]);
                    Logger::log(Logger::INFO, Yii::t('app', 'Assigmnet to employee success'), Yii::$app->user->identity->email);
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'items' => $items
        ]);
    }

    /**
     * Assign or revoke assignment to user
     * @param  integer $id
     * @param  string  $action
     * @return mixed
     */
//    public function actionAssign()
//    {
//        $post = Yii::$app->request->post();
//        $id = $post['id'];
//        $action = $post['action'];
//        $roles = $post['roles'];
//        $manager = Yii::$app->authManager;
//        $error = [];
//        if ($action == 'assign') {
//            foreach ($roles as $name) {
//                try {
//                    $item = $manager->getRole($name);
////                    $item = $item ? : $manager->getPermission($name);
//                    $manager->assign($item, $id);
//                    Logger::log(Logger::INFO, Yii::t('app', 'Assign role to user success. User assign: ') . $id, Yii::$app->user->identity->email);
//                } catch (\Exception $exc) {
//                    $error[] = $exc->getMessage();
//                    Logger::log(Logger::ERROR, Yii::t('app', 'Could not assign role: ') . $exc->getMessage(), Yii::$app->user->identity->email);
//                }
//            }
//        } else {
//            foreach ($roles as $name) {
//                try {
//                    $item = $manager->getRole($name);
////                    $item = $item ? : $manager->getPermission($name);
//                    $manager->revoke($item, $id);
//                    Logger::log(Logger::INFO, Yii::t('app', 'Revoke role success to user: ') . $id, Yii::$app->user->identity->email);
//                } catch (\Exception $exc) {
//                    $error[] = $exc->getMessage();
//                    Logger::log(Logger::ERROR, Yii::t('app', 'Could not revoke role: ') . $exc->getMessage(), Yii::$app->user->identity->email);
//                }
//            }
//        }
//
//        Yii::$app->response->format = Response::FORMAT_JSON;
//        return[
//            'type' => 'S',
//            'errors' => $error,
//        ];
//    }

    /**
     * Search roles of user
     * @param  integer $id
     * @param  string  $target
     * @param  string  $term
     * @return string
     */
//    public function actionSearch($id, $target, $term = '')
//    {
//        Yii::$app->response->format = 'json';
//        $authManager = Yii::$app->authManager;
//        $roles = $authManager->getRoles();
//
//        $avaliable = [];
//        $assigned = [];
//        foreach ($authManager->getAssignments($id) as $assigment) {
//            if (isset($roles[$assigment->roleName])) {
//                if (empty($term) || strpos($assigment->roleName, $term) !== false) {
//                    $assigned['Roles'][$assigment->roleName] = $assigment->roleName;
//                }
//                unset($roles[$assigment->roleName]);
//            }
//        }
//
//        if ($target == 'avaliable') {
//            if (count($roles)) {
//                foreach ($roles as $role) {
//                    if (empty($term) || strpos($role->name, $term) !== false) {
//                        $avaliable['Roles'][$role->name] = $role->name;
//                    }
//                }
//            }
//            return $avaliable;
//        } else {
//            return $assigned;
//        }
//    }

    /**
     * Finds the Assignment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param  integer $id
     * @return Employee the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $class = Yii::$app->getUser()->identityClass ? Yii::$app->getUser()->identityClass : 'backend\models\Employee';
        if (($model = $class::findIdentity($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}