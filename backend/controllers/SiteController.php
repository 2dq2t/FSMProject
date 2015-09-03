<?php
namespace backend\controllers;

use backend\components\Logger;
use backend\components\ParserDateTime;
use backend\models\PasswordResetRequestForm;
use backend\models\ResetPasswordForm;
use Yii;
use yii\base\InvalidParamException;
use yii\data\SqlDataProvider;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use backend\models\LoginForm;
use yii\filters\VerbFilter;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error','request-password-reset', 'reset-password'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        if(Yii::$app->user->isGuest) {
            return $this->renderPartial('login');
        } else {
            \Yii::$app->db->pdo->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
            $top_sale_by_weekly =  Yii::$app->db->createCommand("CALL top_product_sale_by_weekly();")->queryAll();
            $top_sale_by_monthly = Yii::$app->db->createCommand("CALL top_product_sale_by_monthly();")->queryAll();
            $top_customers_orders_by_weekly = Yii::$app->db->createCommand("CALL top_customers_orders_by_weekly();")->queryAll();
            $top_customers_orders_by_monthly = Yii::$app->db->createCommand("CALL top_customers_orders_by_monthly();")->queryAll();
            $last_10_orders_by_weekly = Yii::$app->db->createCommand("CALL last_10_orders_by_weekly();")->queryAll();
            $last_10_orders_by_monthly = Yii::$app->db->createCommand("CALL last_10_orders_by_monthly();")->queryAll();
            $revenue_by_week_of_last_six_month = Yii::$app->db->createCommand("CALL revenue_by_week_of_last_six_month();")->queryAll();
            $revenue_by_last_six_month = Yii::$app->db->createCommand("CALL revenue_by_last_six_month();")->queryAll();
            $orders_by_week_of_last_six_month = Yii::$app->db->createCommand("CALL orders_by_week_of_last_six_month();")->queryAll();
            $orders_of_last_six_month = Yii::$app->db->createCommand("CALL orders_of_last_six_month();")->queryAll();
            $top_sell_products = Yii::$app->db->createCommand("CALL top_ten_sale_product();")->queryAll();
            $total_profit_by_last_six_month = Yii::$app->db->createCommand("CALL total_profit_by_last_six_month();")->queryAll();
            $total_order_by_last_six_month = Yii::$app->db->createCommand("SELECT COUNT(*) AS numberOrder
                        FROM `order` AS o
                        WHERE FROM_UNIXTIME(o.order_date) < NOW() AND DATE_SUB(FROM_UNIXTIME(o.order_date), INTERVAL -6 MONTH )
                        ORDER BY o.order_date DESC;")->queryAll();
            $total_revenue_by_last_six_month = Yii::$app->db->createCommand("SELECT SUM(od.sell_price * od.quantity * (1-od.discount/100)) AS Total
                FROM `order` AS o INNER JOIN order_details AS od ON o.id = od.order_id
                WHERE o.order_status_id = 4 AND FROM_UNIXTIME(o.order_date) < NOW() AND DATE_SUB(FROM_UNIXTIME(o.order_date), INTERVAL -6 MONTH )
                ORDER BY o.order_date DESC ;")->queryAll();
            $total_tax_by_last_six_month = Yii::$app->db->createCommand("SELECT SUM(o.tax_amount) AS Total
                    FROM `order` AS o
                    WHERE o.order_status_id = 4 AND FROM_UNIXTIME(o.order_date) < NOW() AND DATE_SUB(FROM_UNIXTIME(o.order_date), INTERVAL -6 MONTH )
                    ORDER BY o.order_date DESC ;")->queryAll();
            $summary_sale_by_category = Yii::$app->db->createCommand("CALL summary_of_sale_by_category();")->queryAll();
            $summary_sale_by_category_by_years = Yii::$app->db->createCommand("CALL summary_of_sale_by_category_by_year();")->queryAll();
            $summary_of_profit = Yii::$app->db->createCommand("CALL summary_of_profit();")->queryAll();
            $summary_of_orders = Yii::$app->db->createCommand("SELECT COUNT(*) AS Total FROM `order`;")->queryAll();
            $summary_sale_by_location = Yii::$app->db->createCommand("CALL summary_sale_by_location(2);")->queryAll();

            $file_log = glob(Logger::getInstance()->getLogDirectory() . '*.' . Logger::getInstance()->getConfig()['extension']);
            usort($file_log, function($a, $b) {
                return filemtime($a) < filemtime($b);
            });


//            $query->query()->close();

            return $this->render('index', [
                'top_sell_products' => $top_sell_products,
                'total_profit_by_last_six_month' => $total_profit_by_last_six_month[0],
                'total_order_by_last_six_month' => $total_order_by_last_six_month[0],
                'top_sale_by_weekly' => $top_sale_by_weekly,
                'top_sale_by_monthly' => $top_sale_by_monthly,
                'top_customers_orders_by_weekly' => $top_customers_orders_by_weekly,
                'top_customers_orders_by_monthly' => $top_customers_orders_by_monthly,
                'last_10_orders_by_weekly' => $last_10_orders_by_weekly,
                'last_10_orders_by_monthly' => $last_10_orders_by_monthly,
                'revenue_by_week_of_last_six_month' => $revenue_by_week_of_last_six_month,
                'revenue_by_last_six_month' => $revenue_by_last_six_month,
                'orders_by_week_of_last_six_month' => $orders_by_week_of_last_six_month,
                'orders_of_last_six_month' => $orders_of_last_six_month,
                'total_revenue_by_last_six_month' => $total_revenue_by_last_six_month[0],
                'total_tax_by_last_six_month' => $total_tax_by_last_six_month[0],
                'summary_sale_by_category' => $summary_sale_by_category,
                'summary_sale_by_category_by_years' => $summary_sale_by_category_by_years,
                'summary_of_profit' => $summary_of_profit,
                'summary_of_orders' => $summary_of_orders,
                'summary_sale_by_location' => $summary_sale_by_location,
                'file_log' => $file_log[0]
            ]);
        }
    }

    public function actionAdmin(){}
    public function actionManage(){}
    public function actionSale(){}

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            Logger::log(Logger::INFO, Yii::t('app', 'Login success'), Yii::$app->getUser()->id);
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        $userId = Yii::$app->getUser()->id;
        Yii::$app->user->logout();
        Logger::log(Logger::INFO, Yii::t('app', 'Logout success'), $userId);
        return $this->goHome();
    }

    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Check your email for further instructions.'));

                Logger::log(Logger::INFO, Yii::t('app', 'Request reset password'), $model->email);

                return $this->goHome();
            } else {
                Yii::$app->getSession()->setFlash('error', Yii::t('app', 'Sorry, we are unable to reset password for email provided.'));
                Logger::log(Logger::INFO, Yii::t('app', 'Unable send reset password email'), $model->email);
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
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'New password was saved.'));
            Logger::log(Logger::INFO, Yii::t('app', 'New password was saved'), '');
            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionError()
    {
//        if($error=Yii::$app->errorHandler->exception)
//        {
////            var_dump($error);return;
////            if(Yii::$app->request->isAjaxRequest)
////                echo $error['message'];
////            else
//
//        }
//        return $this->renderFile(dir(__FILE__) . 'error', $error);
    }
}
