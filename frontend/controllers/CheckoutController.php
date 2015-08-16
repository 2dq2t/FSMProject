<?php
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 05/08/2015
 * Time: 11:40 CH
 */

namespace frontend\controllers;


use common\models\Customer;
use common\models\OrderAddress;
use common\models\Product;
use frontend\models\CheckoutInfo;
use kartik\alert\Alert;
use yii\web\Controller;
use yii;
use common\models\LoginForm;
use common\models\Guest;
use common\models\Address;
use common\models\District;
use common\models\City;
use common\models\Order;
use common\models\OrderDetails;
use common\models\Voucher;
use backend\components\ParserDateTime;

class CheckoutController extends Controller {

    const SHIPPING_FEE = 0;
    const ORDER_STATUS_ID = 1;
    public function actionCheckout()
    {
        $product_cart = Yii::$app->session->get('product_cart');
        if (empty($product_cart)) {
            return $this->goHome();
        } else {
            $modelLogin = new LoginForm();
            $modelCheckoutInfo = new CheckoutInfo();
            if (Yii::$app->request->isGet) {
                if (Yii::$app->user->isGuest) {
                    $continueStep1 = ' in';
                    $hideStep2 = 'hide';
                    return $this->render('checkout', [
                        'modelLogin' => $modelLogin,
                        'continueStep1' => $continueStep1,
                        'hideStep2'=>$hideStep2,
                    ]);
                } else {
                    $customer = Customer::find()->select(['guest_id', 'address_id'])->where(['id' => Yii::$app->user->identity->getId()])->one();
                    $modelGuest = Guest::find()->where(['id' => $customer['guest_id']])->one();
                    $modelUpdatedAddress = Address::find()->where(['id' => $customer['address_id']])->one();
                    if ($modelUpdatedAddress == null) {
                        $modelAddress = new Address();
                        $modelCity = new City();
                        $modelDistrict = new District();
                        $continueStep2 = ' in';

                        return $this->render('checkout', [
                            'modelGuest' => $modelGuest,
                            'modelAddress' => $modelAddress,
                            'modelDistrict' => $modelDistrict,
                            'modelCity' => $modelCity,
                            'continueStep2' => $continueStep2,
                            'modelCheckoutInfo' => $modelCheckoutInfo,
                        ]);
                    } else {
                        $modelUpdatedDistrict = District::find()->where(['id' => $modelUpdatedAddress->district_id])->one();
                        $modelUpdatedCity = City::find()->where(['id' => $modelUpdatedDistrict->city_id])->one();
                        $continueStep2 = ' in';

                        return $this->render('checkout', [
                            'modelGuest' => $modelGuest,
                            'modelUpdatedAddress' => $modelUpdatedAddress,
                            'modelUpdatedDistrict' => $modelUpdatedDistrict,
                            'modelUpdatedCity' => $modelUpdatedCity,
                            'continueStep2' => $continueStep2,
                            'modelCheckoutInfo' => $modelCheckoutInfo,
                        ]);
                    }
                }
            }
            else if (Yii::$app->request->post() && !empty($_POST['account'])) {
                if ($_POST['account'] == 'register')
                    return $this->redirect('index.php?r=site/register');
                else {
                    $modelGuest = new Guest();
                    $modelAddress = new Address();
                    $modelCity = new City();
                    $modelDistrict = new District();
                    $continueStep2 = ' in';

                    return $this->render('checkout', [
                        'modelGuest' => $modelGuest,
                        'modelAddress' => $modelAddress,
                        'modelDistrict' => $modelDistrict,
                        'modelCity' => $modelCity,
                        'modelCheckoutInfo' => $modelCheckoutInfo,
                        'modelLogin' => $modelLogin,
                        'continueStep2' => $continueStep2,
                    ]);
                }
            } else if ($modelLogin->load(Yii::$app->request->post()) && $modelLogin->login()) {
                $customer = Customer::find()->select(['guest_id', 'address_id'])->where(['id' => Yii::$app->user->identity->getId()])->one();
                $modelGuest = Guest::find()->where(['id' => $customer['guest_id']])->one();
                $modelUpdatedAddress = Address::find()->where(['id' => $customer['address_id']])->one();
                if ($modelUpdatedAddress == null) {
                    $modelAddress = new Address();
                    $modelCity = new City();
                    $modelDistrict = new District();
                    $continueStep2 = ' in';

                    return $this->render('checkout', [
                        'modelGuest' => $modelGuest,
                        'modelAddress' => $modelAddress,
                        'modelDistrict' => $modelDistrict,
                        'modelCity' => $modelCity,
                        'continueStep2' => $continueStep2,
                        'modelCheckoutInfo' => $modelCheckoutInfo,
                    ]);
                } else {
                    $modelUpdatedDistrict = District::find()->where(['id' => $modelUpdatedAddress->district_id])->one();
                    $modelUpdatedCity = City::find()->where(['id' => $modelUpdatedDistrict->city_id])->one();
                    $continueStep2 = ' in';

                    return $this->render('checkout', [
                        'modelGuest' => $modelGuest,
                        'modelUpdatedAddress' => $modelUpdatedAddress,
                        'modelUpdatedDistrict' => $modelUpdatedDistrict,
                        'modelUpdatedCity' => $modelUpdatedCity,
                        'continueStep2' => $continueStep2,
                        'modelCheckoutInfo' => $modelCheckoutInfo,
                    ]);
                }
            }  else {
                /* var_dump(Yii::$app->request->post());*/;
                if (!empty($_POST['Guest'])) {
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        $guest_data = $_POST['Guest'];
                        $guest = new Guest();
                        $guest->full_name = $guest_data['full_name'];
                        $guest->email = $guest_data['email'];
                        $guest->phone_number = $guest_data['phone_number'];
                        $guest->save();

                        $address_data = $_POST['Address'];
                        $order_address = new OrderAddress();
                        $order_address->detail = $address_data['detail'];
                        $order_address->district_id = $address_data['district_id'];
                        $order_address->save();

                        $product_cart = Yii::$app->session->get('product_cart');
                        $total_net_amount = 0;
                        $total_tax_amount = 0;
                        foreach ($product_cart as $item) {
                            $product_price_tax = Product::find()->select(['price', 'tax'])->where(['id' => $item['product_id']])->one();
                            $product_offer = Yii::$app->checkoutFunctions->getProductOffer($item['product_id']);
                            $product_selling_price = Yii::$app->checkoutFunctions->getProductPrice($product_price_tax['price'], $product_offer) * $item['product_quantity'];
                            $total_net_amount += Yii::$app->checkoutFunctions->getNetAmount($product_selling_price, $product_price_tax['tax'], $item['product_quantity']);
                            $total_tax_amount += Yii::$app->checkoutFunctions->getTaxAmount($product_selling_price, $product_price_tax['tax'], $item['product_quantity']);

                        }

                        $order_date = strtotime(date("m/d/Y"));
                        $checkout_info = $_POST['CheckoutInfo'];
                        $receiving_date = ParserDateTime::parseToTimestamp($checkout_info['receiving_date']);
                        $note = $checkout_info['note'];
                        if (empty($note))
                            $note = 'null';

                        $order = new Order();
                        $order->order_date = $order_date;
                        $order->receiving_date = $receiving_date;
                        $order->shipping_fee = self::SHIPPING_FEE;
                        $order->tax_amount = $total_tax_amount;
                        $order->net_amount = $total_net_amount;
                        $order->description = $note;
                        $order->guest_id = $guest->id;
                        $order->order_status_id = self::ORDER_STATUS_ID;
                        $order->order_address_id = $order_address->id;

                        $order->save();

                        foreach ($product_cart as $item) {
                            $product_price = Product::find()->select(['price'])->where(['id' => $item['product_id']])->one();
                            $product_offer = Yii::$app->checkoutFunctions->getProductOffer($item['product_id']);
                            $product_quantity = $item['product_quantity'];
                            $product_id = $item['product_id'];

                            $order_details = new OrderDetails();
                            $order_details->product_id = $product_id;
                            $order_details->order_id = $order->id;
                            $order_details->sell_price = $product_price['price'];
                            $order_details->quantity = $product_quantity;
                            $order_details->discount = $product_offer;
                            $order_details->save();
                        }
                        if (!empty($_POST['voucher'])) {
                            if (Yii::$app->checkoutFunctions->checkVoucher($_POST['voucher'])) {
                                $voucher = Voucher::find()->where(['code' => $_POST['voucher']])->one();
                                $voucher->order_id = $order->id;
                                $voucher->update();
                            }
                        }
                        $transaction->commit();
                        Yii::$app->session->remove('product_cart');
                        Yii::$app->getSession()->setFlash('successful', [
                            'type' => Alert::TYPE_SUCCESS,
                            'duration' => 5000,
                            'icon' => 'fa fa-plus',
                            'message' => Yii::t('app', 'CheckoutResult SuccessMessage'),
                            'title' => Yii::t('app', 'CheckoutResult SuccessTitle'),
                        ]);

                        return $this->actionGetCheckoutResult($order->id);

                    } catch (\yii\db\Exception $ex) {
                        Yii::$app->getSession()->setFlash('failed', [
                            'type' => Alert::TYPE_DANGER,
                            'duration' => 5000,
                            'icon' => 'fa fa-plus',
                            'message' => Yii::t('app', 'CheckoutResult FailMessage'),
                            'title' => Yii::t('app', 'CheckoutResult FailTitle'),
                        ]);

                        $transaction->rollBack();
                        $order_id = null;
                        return $this->actionGetCheckoutResult($order_id);
                    }

                } else {
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        $address_data = $_POST['Address'];
                        if (!empty($_POST['updateAddress'])) {
                            $address_id = Customer::find()->select(['address_id'])->where(['id' => Yii::$app->user->identity->getId()])->one();
                            $update_customer_address = Address::find()->where(['id' => $address_id['address_id']])->one();
                            $update_customer_address->detail = $address_data['detail'];
                            $update_customer_address->district_id = $address_data['district_id'];
                            $update_customer_address->update();
                        }

                        $order_address = new OrderAddress();
                        $order_address->detail = $address_data['detail'];
                        $order_address->district_id = $address_data['district_id'];
                        $order_address->save();

                        $product_cart = Yii::$app->session->get('product_cart');
                        $total_net_amount = 0;
                        $total_tax_amount = 0;
                        foreach ($product_cart as $item) {
                            $product_price_tax = Product::find()->select(['price', 'tax'])->where(['id' => $item['product_id']])->one();
                            $product_offer = Yii::$app->checkoutFunctions->getProductOffer($item['product_id']);
                            $product_selling_price = Yii::$app->checkoutFunctions->getProductPrice($product_price_tax['price'], $product_offer) * $item['product_quantity'];
                            $total_net_amount += Yii::$app->checkoutFunctions->getNetAmount($product_selling_price, $product_price_tax['tax'], $item['product_quantity']);
                            $total_tax_amount += Yii::$app->checkoutFunctions->getTaxAmount($product_selling_price, $product_price_tax['tax'], $item['product_quantity']);

                        }

                        $guest_id = Customer::find()->select(['guest_id'])->where(['id'=>Yii::$app->user->identity->getId()])->one();
                        $order_date = ParserDateTime::parseToTimestamp(date("m/d/Y"));
                        $checkout_info = $_POST['CheckoutInfo'];
                        $receiving_date = ParserDateTime::parseToTimestamp($checkout_info['receiving_date']);

                        $note = $checkout_info['note'];
                        if (empty($note))
                            $note = 'test';
                        $order = new Order();
                        $order->order_date = $order_date;
                        $order->receiving_date = $receiving_date;
                        $order->shipping_fee = self::SHIPPING_FEE;
                        $order->tax_amount = $total_tax_amount;
                        $order->net_amount = $total_net_amount;
                        $order->description = $note;
                        $order->guest_id = $guest_id['guest_id'];
                        $order->order_status_id = self::ORDER_STATUS_ID;
                        $order->order_address_id = $order_address->id;

                        $order->save();

                        foreach ($product_cart as $item) {
                            $product_price = Product::find()->select(['price'])->where(['id' => $item['product_id']])->one();
                            $product_offer = Yii::$app->checkoutFunctions->getProductOffer($item['product_id']);
                            $product_quantity = $item['product_quantity'];
                            $product_id = $item['product_id'];

                            $order_details = new OrderDetails();
                            $order_details->product_id = $product_id;
                            $order_details->order_id = $order->id;
                            $order_details->sell_price = $product_price['price'];
                            $order_details->quantity = $product_quantity;
                            $order_details->discount = $product_offer;
                            $order_details->save();
                        }
                        if (!empty($_POST['voucher'])) {
                            if (Yii::$app->checkoutFunctions->checkVoucher($_POST['voucher'])) {
                                echo Yii::$app->checkoutFunctions->checkVoucher($_POST['voucher']);
                                $voucher = Voucher::find()->where(['code' => $_POST['voucher']])->one();
                                $voucher->order_id =$order->id;
                                $voucher->update();
                            }
                        }
                        $transaction->commit();
                        Yii::$app->session->remove('product_cart');
                        Yii::$app->getSession()->setFlash('successful', [
                            'type' => Alert::TYPE_SUCCESS,
                            'duration' => 5000,
                            'icon' => 'fa fa-plus',
                            'message' => Yii::t('app', 'CheckoutResult SuccessMessage'),
                            'title' => Yii::t('app', 'CheckoutResult SuccessTitle'),
                        ]);
                        return $this->actionGetCheckoutResult($order->id);
                    } catch (yii\base\Exception $ex) {
                        Yii::$app->getSession()->setFlash('failed', [
                            'type' => Alert::TYPE_DANGER,
                            'duration' => 5000,
                            'icon' => 'fa fa-plus',
                            'message' => Yii::t('app', 'CheckoutResult FailMessage'),
                            'title' => Yii::t('app', 'CheckoutResult FailTitle'),
                        ]);
                        $transaction->rollBack();
                        $order_id = null;
                        return $this->actionGetCheckoutResult($order_id);
                    }
                }

            }
        }
    }

    public function actionGetCheckoutResult($order_id)
    {
        if (empty($order_id)) {
            $order = null;
            return $this->render('getCheckoutResult', ['order' => $order]);
        } else {
            $order = Order::find()->where(['id' => $order_id])->one();
            $customer_info = Guest::find()->where(['id' => $order['guest_id']])->one();
            $address = OrderAddress::find()->where(['id' => $order['order_address_id']])->one();
            $district = District::find()->where(['id' => $address['district_id']])->one();
            $city = City::find()->where(['id' => $district['city_id']])->one();
            return $this->render('getCheckoutResult', ['order' => $order, 'customer_info' => $customer_info,
                'address' => $address, 'district' => $district, 'city' => $city,
            ]);
        }
    }

    public function actionCheckVoucher()
    {
        $json = array();
        if (Yii::$app->request->post()) {
            $post_data = Yii::$app->request->post();
            $voucher = $post_data['voucher'];
            $check_voucher = Voucher::find()->where(['code' => $voucher])->one();
            $json['info'] = $voucher;
            $today = date_create_from_format('d/m/Y', date("d/m/Y")) ?
                mktime(null, null, null, date_create_from_format('d/m/Y', date("d/m/Y"))->format('m'), date_create_from_format('d/m/Y', date("d/m/Y"))->format('d'), date_create_from_format('d/m/Y', date("d/m/Y"))->format('y')) : time();
            $voucher_start_date = date("d/m/Y", $check_voucher['start_date']);
            $voucher_end_date = date("d/m/Y", $check_voucher['end_date']);
            if (empty($check_voucher)) {
                $json['error'] = Yii::t('app', 'InputVoucherMsg04');
            } else if ($today < $check_voucher['start_date']) {
                $json['error'] = Yii::t('app', 'InputVoucherMsg02') . $voucher_start_date;
            } else if ($today > $check_voucher['end_date']) {
                $json['error'] = Yii::t('app', 'InputVoucherMsg03') . $voucher_end_date;
            } elseif (!empty($check_voucher['order_id'])) {
                $json['error'] = Yii::t('app', 'InputVoucherMsg05');
            } else if ($check_voucher['active'] == 1) {
                $discount = $check_voucher['discount'];
                $json['success'] = "Bạn được giảm giá " . $discount . "% cho mã giảm giá: " . $voucher . " (mã giảm giá áp dụng với giá trước thuế).</br>Số tiền bạn phải trả còn lại: " . number_format(Yii::$app->checkoutFunctions->getTotalPriceWithVoucher($discount)) . "đ";
            } else {
                $json['error'] = Yii::t('app', 'InputVoucherMsg01');
            }

        } else {
            return $this->goHome();
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [json_encode($json)];
    }
}