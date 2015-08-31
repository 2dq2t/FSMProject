<?php

namespace backend\controllers;

use backend\components\Logger;
use backend\components\ParserDateTime;
use backend\models\Employee;
use common\models\Guest;
use Yii;
use backend\models\Email;
use backend\models\EmailSearch;
use yii\base\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EmailController implements the CRUD actions for Email model.
 */
class EmailController extends Controller
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
     * Lists all Email models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EmailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSendemail()
    {
        $model = new Email();
        if ($model->load(Yii::$app->request->post())) {
            $model->create_at = ParserDateTime::getTimeStamp();
            $model->employee_id = Yii::$app->getUser()->id;

            try {
                if ($model->save()) {
                    $message = \Yii::$app->mailer->compose('marketting-html', ['message' => $model->message])
                        ->setFrom([\Yii::$app->params['supportEmail'] => Yii::$app->params['applicationName']])
                        ->setSubject($model->subject);

                    $requiredTotalSend = 0;
                    $numSent = 0;
                    switch (Yii::$app->request->post('to')) {
                        case "customer_all":
                            $sendto = Guest::find()->select('email, full_name')->all();
                            foreach ($sendto as $key => $value) {
                                $message->setTo([$value->email => $value->full_name]);
                                $numSent += $message->send();
                                $requiredTotalSend++;
                            }
                            break;
                        case "customer":
                            $sendto = $model->getCustomer();
                            foreach ($sendto as $key => $value) {
                                $message->setTo($value);
                                $numSent += $message->send();
                                $requiredTotalSend++;
                            }
                            break;
                        case "product":
                            $products = implode(',', $model->getProduct());
                            $query = Yii::$app->db->createCommand("CALL get_email_follow_products('{$products}')");
                            $query->prepare(true);
                            $sendto = $query->query()->readAll();
                            foreach ($sendto as $key => $value) {
                                $message->setTo($value['email']);
                                $numSent += $message->send();
                                $requiredTotalSend++;
                            }
                            break;
                    }

                    if ($numSent == $requiredTotalSend) {
                        Yii::$app->getSession()->setFlash('success', [
                            'type' => 'success',
                            'duration' => 0,
                            'icon' => 'fa fa-plus',
                            'message' => Yii::t('app', 'Send email successful'),
                            'title' => Yii::t('app', 'Send Email'),
                        ]);

                        Logger::log(Logger::ERROR, Yii::t('app', 'Send email successful'), Yii::$app->user->identity->email);
                        return $this->redirect('index');
                    } else {
                        Yii::$app->getSession()->setFlash('error', [
                            'type' => 'error',
                            'duration' => 0,
                            'icon' => 'fa fa-plus',
                            'message' => Yii::t('app', 'Some email was not send'),
                            'title' => Yii::t('app', 'Send Email'),
                        ]);

                        Logger::log(Logger::ERROR, Yii::t('app', 'Some email was not send'), Yii::$app->user->identity->email);

                        return $this->render('sendemail', [
                            'model' => $model
                        ]);
                    }

                } else {
                    Yii::$app->getSession()->setFlash('error', [
                        'type' => 'error',
                        'duration' => 0,
                        'icon' => 'fa fa-plus',
                        'message' => current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Create Email error'),
                        'title' => Yii::t('app', 'Create Email'),
                    ]);

                    Logger::log(Logger::ERROR, Yii::t('app', 'Create Email error: ') . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Could not save email.'), Yii::$app->user->identity->email);

                    return $this->render('sendemail', [
                        'model' => $model
                    ]);
                }
            } catch(Exception $e) {
                Yii::$app->getSession()->setFlash('error', [
                    'type' => 'error',
                    'duration' => 0,
                    'icon' => 'fa fa-plus',
                    'message' => $e->getMessage(),
                    'title' => Yii::t('app', 'Create Email'),
                ]);

                Logger::log(Logger::ERROR, Yii::t('app', 'Create Email error: ') . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Could not save email.'), Yii::$app->user->identity->email);

                return $this->render('sendemail', [
                    'model' => $model
                ]);
            }
        }

        return $this->render('sendemail',[
            'model' => $model
        ]);
    }

    /**
     * Finds the Email model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Email the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Email::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
