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
            $query = Yii::$app->db->createCommand("CALL top_product_sale_by_weekly()");
            $query->prepare(true);
            $top_sale_by_weekly = $query->query()->readAll();

            $query = Yii::$app->db->createCommand("CALL top_product_sale_by_monthly()");
            $query->prepare(true);
            $top_sale_by_monthly = $query->query()->readAll();

            $query = Yii::$app->db->createCommand("CALL top_customers_orders_by_weekly()");
            $query->prepare(true);
            $top_customers_orders_by_weekly = $query->query()->readAll();

            $query = Yii::$app->db->createCommand("CALL top_customers_orders_by_monthly()");
            $query->prepare(true);
            $top_customers_orders_by_monthly = $query->query()->readAll();

            $query = Yii::$app->db->createCommand("CALL last_10_orders_by_weekly()");
            $query->prepare(true);
            $last_10_orders_by_weekly = $query->query()->readAll();

            $query = Yii::$app->db->createCommand("CALL last_10_orders_by_monthly()");
            $query->prepare(true);
            $last_10_orders_by_monthly = $query->query()->readAll();

            $query = Yii::$app->db->createCommand("CALL revenue_by_week_of_last_six_month()");
            $query->prepare(true);
            $revenue_by_week_of_last_six_month = $query->query()->readAll();

            $query = Yii::$app->db->createCommand("CALL revenue_by_last_six_month()");
            $query->prepare(true);
            $revenue_by_last_six_month = $query->query()->readAll();

            $query = Yii::$app->db->createCommand("CALL orders_by_week_of_last_six_month()");
            $query->prepare(true);
            $orders_by_week_of_last_six_month = $query->query()->readAll();

            $query = Yii::$app->db->createCommand("CALL orders_of_last_six_month()");
            $query->prepare(true);
            $orders_of_last_six_month = $query->query()->readAll();




            $query = Yii::$app->db->createCommand("CALL top_ten_sale_product()");
            $query->prepare(true);
            $top_sell_products = $query->query()->readAll();

            $query = Yii::$app->db->createCommand("CALL total_profit_by_last_six_month()");
            $query->prepare(true);
            $total_profit_by_last_six_month = $query->query()->readAll();

            $query = Yii::$app->db->createCommand("SELECT COUNT(*) AS numberOrder
                        FROM `order` AS o
                        WHERE FROM_UNIXTIME(o.order_date) < NOW() AND DATE_SUB(FROM_UNIXTIME(o.order_date), INTERVAL -6 MONTH )
                        ORDER BY o.order_date DESC;");
            $query->prepare(true);
            $total_order_by_last_six_month = $query->query()->readAll();

            $query = Yii::$app->db->createCommand("SELECT SUM(od.sell_price * od.quantity * (1-od.discount/100)) AS Total
                FROM `order` AS o INNER JOIN order_details AS od ON o.id = od.order_id
                WHERE o.order_status_id = 4 AND FROM_UNIXTIME(o.order_date) < NOW() AND DATE_SUB(FROM_UNIXTIME(o.order_date), INTERVAL -6 MONTH )
                ORDER BY o.order_date DESC ;");
            $query->prepare(true);
            $total_revenue_by_last_six_month = $query->query()->readAll();

            $query = Yii::$app->db->createCommand("SELECT SUM(o.tax_amount) AS Total
                    FROM `order` AS o
                    WHERE o.order_status_id = 4 AND FROM_UNIXTIME(o.order_date) < NOW() AND DATE_SUB(FROM_UNIXTIME(o.order_date), INTERVAL -6 MONTH )
                    ORDER BY o.order_date DESC ;");
            $query->prepare(true);
            $total_tax_by_last_six_month = $query->query()->readAll();

            $query = Yii::$app->db->createCommand("CALL summary_of_sale_by_category()");
            $query->prepare(true);
            $summary_sale_by_category = $query->query()->readAll();

            $query = Yii::$app->db->createCommand("CALL summary_of_sale_by_category_by_year()");
            $query->prepare(true);
            $summary_sale_by_category_by_years = $query->query()->readAll();

            $query = Yii::$app->db->createCommand("CALL summary_of_profit()");
            $query->prepare(true);
            $summary_of_profit = $query->query()->readAll();

            $query = Yii::$app->db->createCommand("SELECT COUNT(*) AS Total FROM `order`");
            $query->prepare(true);
            $summary_of_orders = $query->query()->readAll();

            $query = Yii::$app->db->createCommand("CALL summary_sale_by_location(2)");
            $query->prepare(true);
            $summary_sale_by_location = $query->query()->readAll();

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
                'summary_sale_by_location' => $summary_sale_by_location
            ]);
        }
    }

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
}
