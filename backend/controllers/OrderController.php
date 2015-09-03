<?php

namespace backend\controllers;

use backend\components\Logger;
use backend\components\ParserDateTime;
use backend\models\OrderDetailsExtend;
use backend\models\OrderStatus;
use backend\models\OrderView;
use backend\models\OrderViewSearch;
use common\models\City;
use common\models\District;
use common\models\Guest;
use common\models\Image;
use common\models\Offer;
use common\models\OrderAddress;
use common\models\OrderDetails;
use common\models\Product;
use common\models\Unit;
use common\models\Voucher;
use Yii;
use common\models\Order;
use yii\base\Exception;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'confirm' => ['post'],
                    'delivered' => ['post'],
                    'cancel' => ['post'],
                    'invoice-print' => ['post']
                ],
            ],
            'access' => [
                'class' => YII_DEBUG ?  \yii\base\ActionFilter::className() : \backend\components\AccessControl::className()
            ],
        ];
    }

    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrderViewSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setPagination(['pageSize' => 20]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Order model.
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
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     * @var $order_detail OrderDetails
     */
    public function actionCreate()
    {
        $model = new Order();
        $guest = new Guest();
        $address = new OrderAddress();
        $city = new City();
        $order_details = [new OrderDetails()];

        // load post data to model
        if ($model->load(Yii::$app->request->post()) &&
            $guest->load(Yii::$app->request->post()) &&
            $address->load(Yii::$app->request->post())) {

            // create multiple OrderDetails model for $order_details
            $order_details = [];
            $order_detail = new OrderDetails();
            $order_details_data = Yii::$app->request->post($order_detail->formName());
            if ($order_details_data && is_array($order_details_data)) {
                foreach ($order_details_data as $i => $item) {
                    $order_details[] = new OrderDetails();
                }
            }
            // load data to each OrderDetails model
            OrderDetails::loadMultiple($order_details, Yii::$app->request->post());

            $transaction = \Yii::$app->db->beginTransaction();
            try {

                if($guest->save() && $address->save()) {
                    $model->order_address_id = $address->id;
                    $model->guest_id = $guest->id;

                    // convert string date time to timestamp
                    $model->order_date = ParserDateTime::getTimeStamp();
                    $model->receiving_date = ParserDateTime::parseToTimestamp($model->receiving_date);

                    // count tax amount and net amount
                    $net_amount = 0;
                    $tax_amount = 0;


                    /** @var $order_detail OrderDetails*/
                    foreach ($order_details as $order_detail) {
                        /** @var $product Product*/
                        $product = Product::find()->where(['id' => $order_detail->product_id])->one();
                        $product_tax = $product['tax'];

                        // set order detail if has error and response user selected product list
                        $order_detail->setProductImage(Image::find()->select('resize_path')->where(['product_id' => $order_detail['product_id']])->one()['resize_path']);
                        $order_detail->setProductUnit(Unit::find()->select('name')->where(['active' => 1, 'id' => $product['unit_id']])->one()['name']);
                        $order_detail->setProductPrice($product['price']);
                        $order_detail->setProductTotal($order_detail['quantity'] * $product['price']);

                        // count real product price if exists offer
                        /** @var $product_offer Offer */
                        $product_offer = Offer::find()->select('discount,start_date,end_date')->where(['active' => 1, 'product_id' => $order_detail->product_id])->one();
                        $discount = isset($product_offer) && ($product_offer->start_date <= time() && time() <= $product_offer->end_date) ? $product_offer->discount : 0;
                        $product_price = $discount > 0 ? $product->price * (1 - $discount/100) : $product->price;

                        $net_amount += $order_detail->quantity * $product_price;
                        $tax_amount += $order_detail->quantity * $product_price * $product_tax/100;
                    }

                    $model->net_amount = $net_amount;
                    $model->tax_amount = $tax_amount;
                    $model->shipping_fee = Yii::$app->params['shippingFee'];

                    $errors = [];
                    if ($model->save()) {
                        $product_id = [];
                        // Save each OrderDetails to database
                        foreach ($order_details as $order_detail) {
                            // check if product is unselected
                            if (!$order_detail->product_id) {
                                $transaction->rollBack();
                                $errors[] = Yii::t('app', 'Product not be empty.');
                                break;
                            }

                            // check if two same product selected
                            if ($product_id && in_array($order_detail->product_id, $product_id)) {
                                $transaction->rollBack();
                                $errors[] = Yii::t('app', 'Each product must be unique.');
                                break;
                            }

                            $product_id[] = $order_detail->product_id;

                            $offer = Offer::find()->select('discount,start_date,end_date')->where(['active' => 1, 'product_id' => $order_detail->product_id])->one();
                            $today = date("d-m-Y");
                            $offer_start_date = date("d-m-Y", $offer['start_date']);
                            $offer_end_date = date("d-m-Y", $offer['end_date']);
                            if ($offer_start_date <= $today && $today <= $offer_end_date) {
                                $product_offer = $offer['discount'];
                            } else {
                                $product_offer = 0;
                            }

                            $order_detail->order_id = $model->id;
                            $order_detail->sell_price = Product::find()->where(['id' => $order_detail->product_id])->one()['price'];
                            $order_detail->discount = $product_offer;


                            /* @var $order_detail OrderDetails*/
                            if (!$order_detail->save()) {
                                $errors[] = current($order_detail->getFirstErrors());
                                break;
                            }
                        }

                        if (!empty($model->getVoucher())) {
                            /* @var $voucher Voucher */
                            if ($voucher = Voucher::findOne(['code' => $model->getVoucher(), 'order_id' => null]))
                            {
                                $voucher->order_id = $model->id;
                                if (!$voucher->save()) {
                                    $errors[] = current($voucher->getFirstErrors());
                                }
                            } else {
                                $errors[] = Yii::t('app', 'Voucher has been used');
                            }
                        }

                        if (!empty($errors)) {

                            if ($model->receiving_date) {
                                $model->receiving_date = date('m/d/Y', $model->receiving_date);
                            }

                            if ($transaction->getIsActive()) {
                                $transaction->rollBack();
                            }

                            Yii::$app->getSession()->setFlash('error', [
                                'type' => 'error',
                                'duration' => 0,
                                'icon' => 'fa fa-plus',
                                'message' => !empty($errors) ? $errors[0] : current($model->getFirstErrors()),
                                'title' => Yii::t('app', 'Add Order'),
                            ]);

                            Logger::log(Logger::ERROR, Yii::t('app', 'Create order errors:') .!empty($errors) ? $errors[0] : current($model->getFirstErrors()) , Yii::$app->user->identity->email);

                            return $this->render('create', [
                                'model' => $model,
                                'guest' => $guest,
                                'address' => $address,
                                'city' => $city,
                                'order_details' => (empty($order_details)) ? [new OrderDetails()] : $order_details,
                            ]);
                        }

                        if ($transaction->getIsActive()) {
                            $transaction->commit();
                        }

                        Yii::$app->getSession()->setFlash('success', [
                            'type' => 'success',
                            'duration' => 3000,
                            'icon' => 'fa fa-plus',
                            'message' => Yii::t('app', 'Add Order successful.'),
                            'title' => Yii::t('app', 'Add Order'),
                        ]);

                        Logger::log(Logger::INFO, Yii::t('app', 'Create order address success.'), Yii::$app->user->identity->email);
                        Logger::log(Logger::INFO, Yii::t('app', 'Create guest success.'), Yii::$app->user->identity->email);
                        Logger::log(Logger::INFO, Yii::t('app', 'Create order success.'), Yii::$app->user->identity->email);
                        Logger::log(Logger::INFO, Yii::t('app', 'Create order details success.'), Yii::$app->user->identity->email);

                        switch (Yii::$app->request->post('action', 'save')) {
                            case 'next':
                                return $this->redirect(['create']);
                            default:
                                return $this->redirect(['index']);
                        }

                    } else {
                        if ($model->receiving_date) {
                            $model->receiving_date = date('m/d/Y', $model->receiving_date);
                        }

                        if ($transaction->getIsActive()) {
                            $transaction->rollBack();
                        }

                        Yii::$app->getSession()->setFlash('error', [
                            'type' => 'error',
                            'duration' => 0,
                            'icon' => 'fa fa-plus',
                            'message' => $model->getFirstErrors() ? current($model->getFirstErrors()) : Yii::t('app', 'Could not create order.'),
                            'title' => Yii::t('app', 'Add Order'),
                        ]);

                        Logger::log(Logger::ERROR, Yii::t('app', 'Create order errors:') .current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Could not create order.') , Yii::$app->user->identity->email);

                        return $this->render('create', [
                            'model' => $model,
                            'guest' => $guest,
                            'address' => $address,
                            'city' => $city,
                            'order_details' => (empty($order_details)) ? [new OrderDetails()] : $order_details,
                        ]);
                    }
                } else {
                    if ($transaction->getIsActive()) {
                        $transaction->rollBack();
                    }

                    Yii::$app->getSession()->setFlash('error', [
                        'type' => 'error',
                        'duration' => 0,
                        'icon' => 'fa fa-plus',
                        'message' => current($address->getFirstErrors()) ? current($address->getFirstErrors()) :current($guest->getFirstErrors()) || Yii::t('app', 'Could not be save the address or guest.'),
                        'title' => Yii::t('app', 'Add Order'),
                    ]);

                    Logger::log(Logger::ERROR, Yii::t('app', 'Create order address or guest errors:') .current($address->getFirstErrors()) ? current($address->getFirstErrors()) :current($guest->getFirstErrors()) || Yii::t('app', 'Could not be save the address or guest.') , Yii::$app->user->identity->email);

                    return $this->render('create', [
                        'model' => $model,
                        'guest' => $guest,
                        'address' => $address,
                        'city' => $city,
                        'order_details' => (empty($order_details)) ? [new OrderDetails()] : $order_details,
                    ]);
                }
            } catch(Exception $e) {
                if ($transaction->getIsActive()) {
                    $transaction->rollBack();
                }

                if ($model->receiving_date) {
                    $model->receiving_date = date('m/d/Y', $model->receiving_date);
                }

                Yii::$app->getSession()->setFlash('error', [
                    'type' => 'error',
                    'duration' => 0,
                    'icon' => 'fa fa-plus',
                    'message' => $e->getMessage() ? $e->getMessage() : Yii::t('app', 'Could not save order.Please try again.'),
                    'title' => Yii::t('app', 'Add Order'),
                ]);

                Logger::log(Logger::ERROR, Yii::t('app', 'Create order errors: ') . $e->getMessage() ? $e->getMessage() : Yii::t('app', 'Could not save order.Please try again.'), Yii::$app->user->identity->email);

                return $this->render('create', [
                    'model' => $model,
                    'guest' => $guest,
                    'address' => $address,
                    'city' => $city,
                    'order_details' => (empty($order_details)) ? [new OrderDetails()] : $order_details,
                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'guest' => $guest,
                'address' => $address,
                'city' => $city,
                'order_details' => (empty($order_details)) ? [new OrderDetails()] : $order_details,
            ]);
        }
    }

    public function actionConfirm($id) {
        $model = $this->findModel($id);

        $model->order_status_id = OrderStatus::CONFIRM_ORDER;
        if ($model->save()) {
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'success',
                'duration' => 3000,
                'icon' => 'fa fa-check',
                'message' => Yii::t('app', 'Confirm order successful!'),
                'title' => Yii::t('app', 'Confirm Order'),
            ]);

            Logger::log(Logger::INFO, Yii::t('app', 'Confirm order successful.'), Yii::$app->user->identity->email);
        } else {
            Yii::$app->getSession()->setFlash('error', [
                'type' => 'error',
                'duration' => 0,
                'icon' => 'fa fa-check',
                'message' => current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Confirm oder failure. Please try again later.'),
                'title' => Yii::t('app', 'Confirm Order'),
            ]);
            Logger::log(Logger::INFO, Yii::t('app', 'Confirm order errors:') . current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Confirm oder failure. Please try again later.'), Yii::$app->user->identity->email);
        }

        return $this->redirect(['index']);
    }

    public function actionDelivered($id) {
        $model = $this->findModel($id);

        $model->order_status_id = OrderStatus::DELIVERED_ORDER;
        if ($model->save()) {
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'success',
                'duration' => 3000,
                'icon' => 'fa fa-check',
                'message' => Yii::t('app', 'Delivered order successful.'),
                'title' => Yii::t('app', 'Delivered Order'),
            ]);
            Logger::log(Logger::INFO, Yii::t('app', 'Delivered order successful.'), Yii::$app->user->identity->email);
        } else {
            Yii::$app->getSession()->setFlash('error', [
                'type' => 'error',
                'duration' => 0,
                'icon' => 'fa fa-check',
                'message' => current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Delivered oder failure. Please try again later.'),
                'title' => Yii::t('app', 'Delivered Order'),
            ]);
            Logger::log(Logger::ERROR, Yii::t('app', 'Delivered order confirm errors:'). current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Delivered oder confirm failure. Please try again later.'), Yii::$app->user->identity->email);
        }

        return $this->redirect(['index']);
    }

    public function actionCancel($id) {
        $model = $this->findModel($id);

        $model->order_status_id = OrderStatus::CANCEL_ORDER;
        if ($model->save()) {
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'success',
                'duration' => 3000,
                'icon' => 'fa fa-check',
                'message' => Yii::t('app', 'Cancel order successful.'),
                'title' => Yii::t('app', 'Cancel Order'),
            ]);
            Logger::log(Logger::INFO, Yii::t('app', 'Cancel order successful.'), Yii::$app->user->identity->email);
        } else {
            Yii::$app->getSession()->setFlash('error', [
                'type' => 'error',
                'duration' => 0,
                'icon' => 'fa fa-check',
                'message' => current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Cancel oder failure. Please try again later.'),
                'title' => Yii::t('app', 'Cancel Order'),
            ]);
            Logger::log(Logger::ERROR, Yii::t('app', 'Cancel order errors:'). current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Cancel oder failure. Please try again later.'), Yii::$app->user->identity->email);
        }

        return $this->redirect(['index']);
    }

    public function actionUpdateDescription($order_id)
    {
        if (Yii::$app->request->post()) {
            $model = $this->findModel($order_id);
            $model->description = Yii::$app->request->post('OrderView')['description'];
            if ($model->save()) {
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'success',
                    'duration' => 3000,
                    'icon' => 'fa fa-check',
                    'message' => Yii::t('app', 'Update order description successful.'),
                    'title' => Yii::t('app', 'Update order description'),
                ]);
                Logger::log(Logger::INFO, Yii::t('app', 'Update order description successful.'), Yii::$app->user->identity->email);
            } else {
                Yii::$app->getSession()->setFlash('error', [
                    'type' => 'error',
                    'duration' => 0,
                    'icon' => 'fa fa-check',
                    'message' => current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Update oder description failure. Please try again later.'),
                    'title' => Yii::t('app', 'Update order description'),
                ]);
                Logger::log(Logger::ERROR, Yii::t('app', 'Update order description errors:'). current($model->getFirstErrors()) ? current($model->getFirstErrors()) : Yii::t('app', 'Update oder description failure. Please try again later.'), Yii::$app->user->identity->email);
            }

            return $this->redirect(['index']);
        }
    }

    public function actionInvoice()
    {
        if (Yii::$app->request->get('ids')) {
            $order_ids = explode(',', Yii::$app->request->get('ids'));
            if (!ctype_digit(implode('', $order_ids))) {
                throw new NotAcceptableHttpException(Yii::t('app', 'Invalid an order id.'));
            }

            $models = [];
            foreach($order_ids as $order_id) {
                if (empty(Order::find()->where(['id' => $order_id])->one())) {
                    throw new BadRequestHttpException(Yii::t('app', 'An order with id = {id} is do not exists', ['id' => $order_id]));
                }
                $models[$order_id]['order_view'] = OrderView::find()->where(['order_id' => $order_id])->one();
                $models[$order_id]['order_details_extend'] = OrderDetailsExtend::find()->where(['order_id' => $order_id])->all();
            }

            return $this->render('invoice', [
                'models' => $models
            ]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'Could not found an order.'));
        }
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
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
                    $data = District::find()->where(['city_id'=>$city_id])->select(['id','name'])->asArray()->all();
                    $out = (count($data) == 0) ? ['' => ''] : $data;
                    echo Json::encode(['output' => $out, 'selected' => '']);
                    return;
                }
            }
            echo Json::encode(['output'=>'', 'selected'=>'']);
        }
    }

    public function actionCheckVoucher() {
        $errors = $success = [];
        if ($voucher_code = Yii::$app->request->post()['voucher']) {
            $voucher = Voucher::find()->where(['code' => $voucher_code])->one();
            $today = ParserDateTime::getTimeStamp();
            $voucher_start_date = date("d/m/Y", $voucher['start_date']);
            $voucher_end_date = date("d/m/Y", $voucher['end_date']);
            if (empty($voucher_code)) {
                $errors[] = Yii::t('app', 'Please enter voucher');
            } else if (empty($voucher)) {
                $errors[] = Yii::t('app', 'InputVoucherMsg04');
            } else if ($today < $voucher['start_date']) {
                $errors[] = Yii::t('app', 'InputVoucherMsg02') . $voucher_start_date;
            } else if ($today > $voucher['end_date']) {
                $errors[] = Yii::t('app', 'InputVoucherMsg03') . $voucher_end_date;
            } elseif (!empty($voucher['order_id'])) {
                $errors[] = Yii::t('app', 'InputVoucherMsg05');
            } else if ($voucher['active'] == 1) {
                $discount = $voucher['discount'];
                $success[] = Yii::t('app', "You get discount {discount}% for discount code {voucher_code}", ['discount' => $discount, 'voucher_code' => $voucher_code]);
            } else {
                $errors[] = Yii::t('app', 'InputVoucherMsg01');
            }
        }

        \Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'errors' => $errors,
            'success' => $success
        ];
    }

    public function actionGetProductInfo($id) {
        /* @var $product_info Product*/
        /* @var $image Image*/
        /** @var $product_offer Offer*/

        $product_info = Product::find()->select('price, quantity_in_stock, sold, unit_id')->where(['id' => $id, 'active' => Product::STATUS_ACTIVE])->one();
        // count real product price if exists offer
//        $product_offer = Offer::find()->select('discount,start_date,end_date')->where(['active' => 1, 'product_id' => $id])->one();
//        $discount = isset($product_offer) && ($product_offer->start_date <= time() && time() <= $product_offer->end_date) ? $product_offer->discount : 0;
//        $product_price = $discount > 0 ? $product_info->price *(1 - $discount/100) : $product_info->price;
        $product_price = $product_info->price;

        $image = Image::find()->select('resize_path')->where(['product_id' => $id])->one();
        $unit = Unit::find()->select('name')->where(['active' => Unit::STATUS_ACTIVE, 'id' => $product_info->unit_id])->one();
        $product_info = [
            'price' => $product_price,
            'quantity_in_stock' => $product_info->quantity_in_stock,
            'sold' => $product_info->sold,
            'unit' => $unit->name,
            'image' => $image ? $image->resize_path : null,
        ];
        echo Json::encode($product_info);
    }
}
