<?php

namespace backend\controllers;

use backend\models\Model;
use backend\models\OrderStatus;
use backend\models\OrderViewSearch;
use common\models\Address;
use common\models\City;
use common\models\District;
use common\models\Guest;
use common\models\Image;
use common\models\OrderDetails;
use common\models\Product;
use common\models\Unit;
use common\models\Voucher;
use common\models\Ward;
use kartik\widgets\Alert;
use Yii;
use common\models\Order;
use common\models\OrderSearch;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
                    'cancel' => ['post']
                ],
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
        $dataProvider->setPagination(['pageSize' => 7]);

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
     */
    public function actionCreate()
    {
        $model = new Order();
        $guest = new Guest();
        $address = new Address();
        $city = new City();
        $order_details = [new OrderDetails()];

//        $model->order_date = date('m/d/Y', $model->order_date);
//        $model->receiving_date = date('m/d/Y', $model->receiving_date);

        // load post data to model
        if ($model->load(Yii::$app->request->post()) &&
            $guest->load(Yii::$app->request->post()) &&
            $address->load(Yii::$app->request->post())) {

            // create multiple OrderDetails model for $order_details
            $order_details = Model::createMultiple(OrderDetails::classname());
            // load data to each OrderDetails model
            Model::loadMultiple($order_details, Yii::$app->request->post());

            $transaction = \Yii::$app->db->beginTransaction();
            try {

                if($address->save() && $guest->save()) {
                    $model->address_id = $address->id;
                    $model->guest_id = $guest->id;

                    // convert string date time to timestamp
                    $model->order_date = strtotime($model->order_date);
                    $model->receiving_date = strtotime($model->receiving_date);

                    // count tax amount and net amount
                    $net_amount = 0;
                    $tax_amount = 0;

                    foreach ($order_details as $order_detail) {
                        $net_amount += Product::find()->where(['id' => $order_detail->product_id])->one()['price']
                            * $order_detail->quantity;
                        $tax_amount += Product::find()->where(['id' => $order_detail->product_id])->one()['price']
                            * $order_detail->quantity * (1-Product::find()->where(['id' => $order_detail->product_id])->one()['tax']);
                    }

                    $model->net_amount = $net_amount;
                    $model->tax_amount = $tax_amount;

                    if ($model->save()) {
                        if ($flag = $model->save()) {
                            $product_id = [];
                            // Save each OrderDetails to database
                            foreach ($order_details as $order_detail) {
                                if (!$order_detail->product_id) {
                                    $transaction->rollBack();
                                    $errors[] = Yii::t('app', 'Product not be empty.');
                                    break;
                                }

                                if ($product_id && in_array($order_detail->product_id, $product_id)) {
                                    $transaction->rollBack();
                                    $errors[] = Yii::t('app', 'Each product must be unique.');
                                    break;
                                }

                                $product_id[] = $order_detail->product_id;

                                $order_detail->order_id = $model->id;
                                $order_detail->sell_price = Product::find()->where(['id' => $order_detail->product_id])->one()['price'];
                                $order_detail->discount = 1;


                                if (! ($flag = $order_detail->save())) {
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }
                        if ($flag) {
                            $transaction->commit();
                            Yii::$app->getSession()->setFlash('success', [
                                'type' => Alert::TYPE_SUCCESS,
                                'duration' => 3000,
                                'icon' => 'fa fa-plus',
                                'message' => Yii::t('app', 'Add Order successful.'),
                                'title' => Yii::t('app', 'Add Order'),
                            ]);

                            switch (Yii::$app->request->post('action', 'save')) {
                                case 'next':
                                    return $this->redirect(['create']);
                                default:
                                    return $this->redirect(['index']);
                            }
                        }
                    } else {
                        if ($model->order_date) {
                            $model->order_date = date('m/d/Y', $model->order_date);
                        }

                        if ($model->receiving_date) {
                            $model->receiving_date = date('m/d/Y', $model->receiving_date);
                        }

                        Yii::$app->getSession()->setFlash('danger', [
                            'type' => Alert::TYPE_DANGER,
                            'duration' => 0,
                            'icon' => 'fa fa-plus',
                            'message' => current($model->getFirstErrors()),
                            'title' => Yii::t('app', 'Add Order'),
                        ]);

                        return $this->render('create', [
                            'model' => $model,
                            'guest' => $guest,
                            'address' => $address,
                            'city' => $city,
                            'order_details' => (empty($order_details)) ? [new OrderDetails()] : $order_details,
                        ]);
                    }
                } else {
                    if ($model->order_date) {
                        $model->order_date = date('m/d/Y', $model->order_date);
                    }

                    if ($model->receiving_date) {
                        $model->receiving_date = date('m/d/Y', $model->receiving_date);
                    }

                    Yii::$app->getSession()->setFlash('danger', [
                        'type' => Alert::TYPE_DANGER,
                        'duration' => 0,
                        'icon' => 'fa fa-plus',
                        'message' => $address->getFirstErrors() ? $address->getFirstErrors() : $guest->getFirstErrors() || 'Could not be save the address or guest.',
                        'title' => Yii::t('app', 'Add Order'),
                    ]);

                    return $this->render('create', [
                        'model' => $model,
                        'guest' => $guest,
                        'address' => $address,
                        'city' => $city,
                        'order_details' => (empty($order_details)) ? [new OrderDetails()] : $order_details,
                    ]);
                }
            } catch(Exception $e) {
                $transaction->rollBack();

                if ($model->order_date) {
                    $model->order_date = date('m/d/Y', $model->order_date);
                }

                if ($model->receiving_date) {
                    $model->receiving_date = date('m/d/Y', $model->receiving_date);
                }

                Yii::$app->getSession()->setFlash('danger', [
                    'type' => Alert::TYPE_DANGER,
                    'duration' => 0,
                    'icon' => 'fa fa-plus',
                    'message' => $e->getMessage(),
                    'title' => Yii::t('app', 'Add Order'),
                ]);

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
        } else {
            Yii::$app->getSession()->setFlash('error', [
                'type' => 'error',
                'duration' => 0,
                'icon' => 'fa fa-check',
                'message' => $model->getFirstErrors() ? $model->getFirstErrors() : Yii::t('app', 'Confirm oder failure. Please try again later/'),
                'title' => Yii::t('app', 'Confirm Order'),
            ]);
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
                'message' => Yii::t('app', 'Delivered order successful!'),
                'title' => Yii::t('app', 'Delivered Order'),
            ]);
        } else {
            Yii::$app->getSession()->setFlash('error', [
                'type' => 'error',
                'duration' => 0,
                'icon' => 'fa fa-check',
                'message' => $model->getFirstErrors() ? $model->getFirstErrors() : Yii::t('app', 'Delivered oder failure. Please try again later/'),
                'title' => Yii::t('app', 'Delivered Order'),
            ]);
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
                'message' => Yii::t('app', 'Cancel order successful!'),
                'title' => Yii::t('app', 'Cancel Order'),
            ]);
        } else {
            Yii::$app->getSession()->setFlash('error', [
                'type' => 'error',
                'duration' => 0,
                'icon' => 'fa fa-check',
                'message' => $model->getFirstErrors() ? $model->getFirstErrors() : Yii::t('app', 'Cancel oder failure. Please try again later/'),
                'title' => Yii::t('app', 'Cancel Order'),
            ]);
        }

        return $this->redirect(['index']);
    }

    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
//    public function actionUpdate($id)
//    {
//        $model = $this->findModel($id);
//        $guest = Guest::find()->where(['id' => $model->guest_id])->one();
//        $address = Address::find()->where(['id' => $model->address_id])->one();
//        $district = District::find()->where(['id' => $address->district_id])->one();
//        $city = City::find()->where(['id'=>$district->city_id])->one();
//        $order_details = $model->orderDetails;
//
//        $model->order_date = date('m/d/Y', $model->order_date);
//        $model->receiving_date = date('m/d/Y', $model->receiving_date);
//
//        // set field value for each order_detail
//        foreach($order_details as $i => $order_detail){
//            $order_detail['product_image'] = Image::find()->select('path')->where(['product_id' => $order_detail['product_id']])->one()['path'];
//            $unit_id = Product::find()->where(['id' => $order_detail['product_id']])->one()['unit_id'];
//            $order_detail['product_unit'] = Unit::find()->select('name')->where(['active' => 1, 'id' => $unit_id])->one()['name'];
//            $order_detail['product_total'] = $order_detail['quantity'] * $order_detail['sell_price'];
//            $order_detail['max_quantity'] = Product::find()->where(['id' => $order_detail['product_id']])->one()['quantity_in_stock'] - Product::find()->where(['id' => $order_detail['product_id']])->one()['sold'];
//            $order_detail['tax'] = Product::find()->where(['id' => $order_detail['product_id']])->one()['tax'];
//        }
//
//
//        if ($model->load(Yii::$app->request->post())  &&
//            $guest->load(Yii::$app->request->post()) &&
//            $address->load(Yii::$app->request->post())) {
//
//            $oldIDs = ArrayHelper::map($order_details, 'order_id', 'order_id');
//            $order_details = Model::createMultiple(OrderDetails::classname(), $order_details, 'order_id');
//            Model::loadMultiple($order_details, Yii::$app->request->post());
//            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($order_details, 'order_id', 'order_id')));
//
//            $errors = [];
//
//            $net_amount = 0;
//            $tax_amount = 0;
//
//            foreach ($order_details as $order_detail) {
//                $net_amount += $order_detail->quantity * $order_detail->sell_price;
//                $tax_amount += $order_detail->quantity * $order_detail->sell_price * (1 - $order_detail->tax);
//            }
//            $model->net_amount = $net_amount;
//            $model->tax_amount = $tax_amount;
//
//            $transaction = \Yii::$app->db->beginTransaction();
//            try {
//                if($address->save() && $guest->save()) {
//                    $model->address_id = $address->id;
//                    $model->guest_id = $guest->id;
//                    $model->order_date = strtotime($model->order_date);
//                    $model->receiving_date = strtotime($model->receiving_date);
//
//                    // validate models
//                    $valid = $model->validate();
////                    $valid = Model::validateMultiple($order_details) && $valid;
//
//
//                    if ($valid && $model->save()) {
//
//                        if ($flag = $model->save()) {
//                            if (! empty($deletedIDs)) {
//                                OrderDetails::deleteAll(['order_id' => $deletedIDs]);
//                            }
//
//                            $product_id = [];
//
//                            foreach ($order_details as $order_detail) {
//
//                                if (!$order_detail->product_id) {
//                                    $transaction->rollBack();
//                                    $errors[] = Yii::t('app', 'Product not be empty.');
//                                    break;
//                                }
//
//                                // check if have two product is same
//                                if ($product_id && in_array($order_detail->product_id, $product_id)) {
//                                    $transaction->rollBack();
//                                    $errors[] = Yii::t('app', 'Each product must be unique.');
//                                    break;
//                                }
//
//                                // check if have new product added, set order_detail attribute
//                                if (!$order_detail->order_id) {
//                                    $order_detail->order_id = $model->id;
//                                    $order_detail->sell_price = Product::find()->where(['id' => $order_detail->product_id])->one()['price'];
//                                }
//
//                                // if save order_detail fail and roll back all saved data
//                                if (! ($flag = $order_detail->save())) {
//                                    $transaction->rollBack();
//                                    break;
//                                }
//                            }
//                        }
//                        if ($flag) {
//                            $transaction->commit();
//
//                            Yii::$app->getSession()->setFlash('success', [
//                                'type' => Alert::TYPE_SUCCESS,
//                                'duration' => 3000,
//                                'icon' => 'fa fa-plus',
//                                'message' => Yii::t('app', 'Edit Order successful.'),
//                                'title' => Yii::t('app', 'Edit Order'),
//                            ]);
//
//                            switch (Yii::$app->request->post('action', 'save')) {
//                                case 'next':
//                                    return $this->redirect(['create']);
//                                default:
//                                    return $this->redirect(['index']);
//                            }
//                        }
//                    } else {
//                        if ($model->order_date) {
//                            $model->order_date = date('m/d/Y', $model->order_date);
//                        }
//
//                        if ($model->receiving_date) {
//                            $model->receiving_date = date('m/d/Y', $model->receiving_date);
//                        }
//
//                        Yii::$app->getSession()->setFlash('danger', [
//                            'type' => Alert::TYPE_DANGER,
//                            'duration' => 3000,
//                            'icon' => 'fa fa-pencil',
//                            'message' => current($model->getFirstErrors()) ? current($model->getFirstErrors()) : 'Could not be save the order',
//                            'title' => Yii::t('app', 'Edit Order'),
//                        ]);
//
//                        return $this->render('update', [
//                            'model' => $model,
//                            'guest' => $guest,
//                            'address' => $address,
//                            'city' => $city,
//                            'order_details' => (empty($order_details)) ? [new OrderDetails()] : $order_details,
//                        ]);
//                    }
//                } else {
//                    if ($model->order_date) {
//                        $model->order_date = date('m/d/Y', $model->order_date);
//                    }
//
//                    if ($model->receiving_date) {
//                        $model->receiving_date = date('m/d/Y', $model->receiving_date);
//                    }
//
//                    Yii::$app->getSession()->setFlash('danger', [
//                        'type' => Alert::TYPE_DANGER,
//                        'duration' => 3000,
//                        'icon' => 'fa fa-pencil',
//                        'message' => current($address->getFirstErrors()) ? current($address->getFirstErrors()) : $guest->getFirstErrors() || 'Could not be save the address/',
//                        'title' => Yii::t('app', 'Edit Order'),
//                    ]);
//
//                    return $this->render('update', [
//                        'model' => $model,
//                        'guest' => $guest,
//                        'address' => $address,
//                        'city' => $city,
//                        'order_details' => (empty($order_details)) ? [new OrderDetails()] : $order_details,
//                    ]);
//                }
//
//            } catch(Exception $e) {
//                $transaction->rollBack();
//
//                if ($model->order_date) {
//                    $model->order_date = date('m/d/Y', $model->order_date);
//                }
//
//                if ($model->receiving_date) {
//                    $model->receiving_date = date('m/d/Y', $model->receiving_date);
//                }
//
//                Yii::$app->getSession()->setFlash('danger', [
//                    'type' => Alert::TYPE_DANGER,
//                    'duration' => 3000,
//                    'icon' => 'fa fa-pencil',
//                    'message' => $e->getMessage(),
//                    'title' => Yii::t('app', 'Edit Order'),
//                ]);
//
//                return $this->render('update', [
//                    'model' => $model,
//                    'guest' => $guest,
//                    'address' => $address,
//                    'city' => $city,
//                    'order_details' => (empty($order_details)) ? [new OrderDetails()] : $order_details,
//                ]);
//            }
//
//
//            return $this->redirect(['view', 'id' => $model->id]);
//        } else {
//            return $this->render('update', [
//                'model' => $model,
//                'guest' => $guest,
//                'address' => $address,
//                'city' => $city,
//                'order_details' => (empty($order_details)) ? [new OrderDetails()] : $order_details,
//            ]);
//        }
//    }

    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
//    public function actionDelete($id)
//    {
//        if (OrderDetails::find()->where(['order_id' => $id])->all()) {
//            Yii::$app->getSession()->setFlash('warning', [
//                'type' => Alert::TYPE_WARNING,
//                'duration' => 0,
//                'icon' => 'fa fa-trash-o',
//                'message' => Html::encode('Order has one or more order details') . '</br>'
//                    . Html::encode('Do you wish to delete all?')
//                    . Html::a(Yii::t('app',' Delete all'), ['delete-all', 'id' => $id]  , ['class' => 'btn btn-primary alert-link']),
//                'title' => 'Delete Order'
//            ]);
//        } else {
//
//            $this->findModel($id)->delete();
//
//            Yii::$app->getSession()->setFlash('success', [
//                'type' => Alert::TYPE_SUCCESS,
//                'duration' => 3000,
//                'icon' => 'fa fa-trash-o',
//                'message' => 'Order Record has been deleted.',
//                'title' => 'Delete Season'
//            ]);
//        }
//
//        return $this->redirect(['index']);
//    }

    /**
     * Deletes an all Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
//    public function actionDeleteAll($id) {
//        try {
//            $transaction = Yii::$app->db->beginTransaction();
//            // delete all order_details where order_detail.order_id = $id
//            OrderDetails::deleteAll('order_id = :order_id', [':order_id' => $id]);
//
//            // delete all voucher where voucher.order_id = $id
//            Voucher::deleteAll('order_id = :order_id', [':order_id' => $id]);
//
//            $this->findModel($id)->delete();
//
//            $transaction->commit();
//
//            Yii::$app->getSession()->setFlash('success', [
//                'type' => Alert::TYPE_SUCCESS,
//                'duration' => 3000,
//                'icon' => 'fa fa-trash-o',
//                'message' => 'Order Record has been deleted.',
//                'title' => 'Delete Season'
//            ]);
//
//            return $this->redirect(['index']);
//        } catch (Exception $e) {
//            $transaction->rollBack();
//            Yii::$app->getSession()->setFlash('error', [
//                'type' => Alert::TYPE_DANGER,
//                'duration' => 3000,
//                'icon' => 'fa fa-trash-o',
//                'message' => 'Order delete errors. Errors: ' . $e->getMessage(),
//                'title' => 'Delete Order'
//            ]);
//        }
//    }

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

    public function actionGetProductInfo($id) {
        $product_info = Product::find()->select('price, quantity_in_stock, sold, unit_id')->where(['id' => $id, 'active' => Product::STATUS_ACTIVE])->one();
        $image = Image::find()->select('path')->where(['product_id' => $id])->one();
        $unit = Unit::find()->select('name')->where(['active' => Unit::STATUS_ACTIVE, 'id' => $product_info->unit_id])->one();
        $product_info = [
            'price' => $product_info->price,
            'quantity_in_stock' => $product_info->quantity_in_stock,
            'sold' => $product_info->sold,
            'unit' => $unit->name,
            'image' => $image ? $image->path : null,
        ];
        echo Json::encode($product_info);
    }
}
