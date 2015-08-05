<?php
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 05/08/2015
 * Time: 11:40 CH
 */

namespace frontend\controllers;


use kartik\alert\Alert;
use yii\web\Controller;
use yii;
use yii\db\Query;
use common\models\LoginForm;
use frontend\models\CheckoutInfo;
use common\models\Guest;
use common\models\Address;
use common\models\District;
use common\models\City;
use common\models\Order;
use common\models\OrderDetails;
use common\models\Voucher;

class CheckoutController extends Controller {

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
                    $customer = (new Query())->select(['guest_id', 'address_id'])->from('customer')->where(['id' => Yii::$app->user->identity->getId()])->one();
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
                        'continueStep2' => $continueStep2,
                    ]);
                }
            } else if ($modelLogin->load(Yii::$app->request->post()) && $modelLogin->login()) {
                $customer = (new Query())->select(['guest_id', 'address_id'])->from('customer')->where(['id' => Yii::$app->user->identity->getId()])->one();
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
                        $guest_address = new Address();
                        $guest_address->detail = $address_data['detail'];
                        $guest_address->district_id = $address_data['district_id'];
                        $guest_address->save();

                        $product_cart = Yii::$app->session->get('product_cart');
                        $total_net_amount = 0;
                        $total_tax_amount = 0;
                        foreach ($product_cart as $item) {
                            $product_price_tax = (new Query())->select(['price', 'tax'])->from('product')->where(['id' => $item['product_id']])->one();
                            $product_offer = Yii::$app->CommonFunction->getProductOffer($item['product_id']);
                            $product_selling_price = Yii::$app->CommonFunction->getProductPrice($product_price_tax['price'], $product_offer) * $item['product_quantity'];
                            $total_net_amount += Yii::$app->CommonFunction->getNetAmount($product_selling_price, $product_price_tax['tax'], $item['product_quantity']);
                            $total_tax_amount += Yii::$app->CommonFunction->getTaxAmount($product_selling_price, $product_price_tax['tax'], $item['product_quantity']);

                        }
                        $guest_id = (new Query())->select(['id'])->from('guest')->where(['full_name' => $guest_data['full_name'], 'email' => $guest_data['email'], 'phone_number' => $guest_data['phone_number']])->one();
                        $guest_address_id = (new Query())->select(['id'])->from('address')->where(['detail' => $address_data['detail'], 'district_id' => $address_data['district_id']])->one();
                        $order_date = strtotime(date("m/d/Y"));
                        $checkout_info = $_POST['CheckoutInfo'];
                        $receiving_date = strtotime(date($checkout_info['receiving_date']));
                        $note = $checkout_info['note'];
                        if (empty($note))
                            $note = 'null';

                        $order = new Order();
                        $order->order_date = $order_date;
                        $order->receiving_date = $receiving_date;
                        $order->shipping_fee = 0;
                        $order->tax_amount = $total_tax_amount;
                        $order->net_amount = $total_net_amount;
                        $order->description = $note;
                        $order->guest_id = $guest_id['id'];
                        $order->order_status_id = 1;
                        $order->address_id = $guest_address_id['id'];

                        $order->save();
                        $order_id = (new Query())->select(['id'])->from('order')->where(['order_date' => $order_date, 'receiving_date' => $receiving_date,
                            'tax_amount' => $total_tax_amount, 'net_amount' => $total_net_amount, 'description' => $note,
                            'guest_id' => $guest_id['id'], 'address_id' => $guest_address_id['id']])->one();
                        foreach ($product_cart as $item) {
                            $product_price = (new Query())->select(['price'])->from('product')->where(['id' => $item['product_id']])->one();
                            $product_offer = Yii::$app->CommonFunction->getProductOffer($item['product_id']);
                            $product_quantity = $item['product_quantity'];
                            $product_id = $item['product_id'];

                            $order_details = new OrderDetails();
                            $order_details->product_id = $product_id;
                            $order_details->order_id = $order_id['id'];
                            $order_details->sell_price = $product_price['price'];
                            $order_details->quantity = $product_quantity;
                            $order_details->discount = $product_offer;
                            $order_details->save();
                        }
                        if (!empty($_POST['voucher'])) {
                            if (Yii::$app->CommonFunction->checkVoucher($_POST['voucher'])) {
                                $voucher = Voucher::find()->where(['code' => $_POST['voucher']])->one();
                                $voucher->order_id = $order_id['id'];
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

                        return $this->actionGetCheckoutResult($order_id['id']);

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
                        if (!empty($_POST['updateAddress'])) {
                            $address_data = $_POST['Address'];
                            $address_id = (new Query())->select(['address_id'])->from('customer')->where(['id' => Yii::$app->user->identity->getId()])->one();
                            $update_customer_address = Address::find()->where(['id' => $address_id['address_id']])->one();
                            $update_customer_address->detail = $address_data['detail'];
                            $update_customer_address->district_id = $address_data['district_id'];
                            $update_customer_address->update();
                        }
                        $product_cart = Yii::$app->session->get('product_cart');
                        $total_net_amount = 0;
                        $total_tax_amount = 0;
                        foreach ($product_cart as $item) {
                            $product_price_tax = (new Query())->select(['price', 'tax'])->from('product')->where(['id' => $item['product_id']])->one();
                            $product_offer = Yii::$app->CommonFunction->getProductOffer($item['product_id']);
                            $product_selling_price = Yii::$app->CommonFunction->getProductPrice($product_price_tax['price'], $product_offer) * $item['product_quantity'];
                            $total_net_amount += Yii::$app->CommonFunction->getNetAmount($product_selling_price, $product_price_tax['tax'], $item['product_quantity']);
                            $total_tax_amount += Yii::$app->CommonFunction->getTaxAmount($product_selling_price, $product_price_tax['tax'], $item['product_quantity']);

                        }
                        $customer_info = (new Query())->select(['guest_id', 'address_id'])->from('customer')->where(['id' => Yii::$app->user->identity->getId()])->one();
                        $order_date = strtotime(date("m/d/Y"));
                        $checkout_info = $_POST['CheckoutInfo'];
                        $receiving_date = strtotime(date($checkout_info['receiving_date']));
                        $note = $checkout_info['note'];
                        if (empty($note))
                            $note = 'null';
                        $order = new Order();
                        $order->order_date = $order_date;
                        $order->receiving_date = $receiving_date;
                        $order->shipping_fee = 0;
                        $order->tax_amount = $total_tax_amount;
                        $order->net_amount = $total_net_amount;
                        $order->description = $note;
                        $order->guest_id = $customer_info['guest_id'];
                        $order->order_status_id = 1;
                        $order->address_id = $customer_info['address_id'];

                        $order->save();
                        $order_id = (new Query())->select(['id'])->from('order')->where(['order_date' => $order_date, 'receiving_date' => $receiving_date,
                            'tax_amount' => $total_tax_amount, 'net_amount' => $total_net_amount, 'description' => $note,
                            'guest_id' => $customer_info['guest_id'], 'address_id' => $customer_info['address_id']])->one();
                        foreach ($product_cart as $item) {
                            $product_price = (new Query())->select(['price'])->from('product')->where(['id' => $item['product_id']])->one();
                            $product_offer = Yii::$app->CommonFunction->getProductOffer($item['product_id']);
                            $product_quantity = $item['product_quantity'];
                            $product_id = $item['product_id'];

                            $order_details = new OrderDetails();
                            $order_details->product_id = $product_id;
                            $order_details->order_id = $order_id['id'];
                            $order_details->sell_price = $product_price['price'];
                            $order_details->quantity = $product_quantity;
                            $order_details->discount = $product_offer;
                            $order_details->save();
                        }
                        if (!empty($_POST['voucher'])) {
                            if (Yii::$app->CommonFunction->checkVoucher($_POST['voucher'])) {
                                echo Yii::$app->CommonFunction->checkVoucher($_POST['voucher']);
                                $voucher = Voucher::find()->where(['code' => $_POST['voucher']])->one();
                                $voucher->order_id = $order_id['id'];
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
                        return $this->actionGetCheckoutResult($order_id['id']);
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
            $address = Address::find()->where(['id' => $order['address_id']])->one();
            $district = District::find()->where(['id' => $address['district_id']])->one();
            $city = City::find()->where(['id' => $district['city_id']])->one();
            return $this->render('getCheckoutResult', ['order' => $order, 'customer_info' => $customer_info,
                'address' => $address, 'district' => $district, 'city' => $city,
            ]);
        }

    }
}