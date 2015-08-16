<?php
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 05/08/2015
 * Time: 11:17 CH
 */

namespace frontend\controllers;

use common\models\Product;
use yii\web\Controller;
use Yii;


class CartController extends Controller{

    public $enableCsrfValidation = false;

    public function actionAddToCart()
    {
        $json = array();
        $flag = true;
        if (Yii::$app->request->post()) {
            $post_data = Yii::$app->request->post();
            if (isset($post_data['product_id'])) {
                $product_id = $post_data['product_id'];
            } else {
                $product_id = null;
            }
            if ($product_id == null) {
                $json['error'] = Yii::t('app', 'AddtoCartMsg01');
            } elseif (!((string)(int)$post_data['quantity'] == $post_data['quantity'])) {
                $json['error'] = Yii::t('app', 'AddtoCartMsg03');
            } elseif ($post_data['quantity'] <= 0) {
                $json['error'] = Yii::t('app', 'AddtoCartMsg04');
            } else {
                $total_price = 0;
                $total_product = 0;
                if (isset($post_data['quantity'])) {
                    $product_quantity = $post_data['quantity'];
                } else {
                    $product_quantity = 1;
                }
                $product_cart = Yii::$app->session->get('product_cart');
                $product['product_id'] = $product_id;
                $product['product_quantity'] = $product_quantity;
                $product_price = Product::find()->select(['price'])->where(['id' => $product_id])->one();
                $product_offer = Yii::$app->CommonFunction->getProductOffer($product_id);
                $total_price += Yii::$app->CommonFunction->getProductPrice($product_price['price'], $product_offer) * $product_quantity;
                $total_product += $product_quantity;
                if (count($product_cart) == 0) {
                    Yii::$app->session->set('product_cart', [$product]);
                    $json['success'] = Yii::t('app', 'AddtoCartMsg02');
                    $json['total'] = $total_product . " Sản phẩm - " . number_format($total_price) ." ". Yii::t('app', 'VNDLabel');
                } else {
                    foreach ($product_cart as $key => $item) {
                        $product_price = Product::find()->select('price')->where(['id' => $item['product_id']])->one();
                        $product_offer = Yii::$app->CommonFunction->getProductOffer($item['product_id']);
                        $total_price += Yii::$app->CommonFunction->getProductPrice($product_price['price'], $product_offer) * $item['product_quantity'];
                        $total_product += $item['product_quantity'];
                        if (($item['product_id'] == $product['product_id'])) {
                            $product_cart[$key]['product_quantity'] = $item['product_quantity'] + $product_quantity;
                            $flag = false;
                        }
                    }
                    if ($flag) {
                        array_push($product_cart, $product);
                        Yii::$app->session->set('product_cart', $product_cart);
                    } else {
                        Yii::$app->session->set('product_cart', $product_cart);
                    }
                    $json['success'] = Yii::t('app', 'AddtoCartMsg02');
                    $json['total'] = ($total_product) . " Sản phẩm - " . number_format($total_price) ." ". Yii::t('app', 'VNDLabel');
                }
            }
        } else {
            return $this->goHome();
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return json_encode($json);
    }

    public function actionGetCartInfo()
    {
        return $this->renderPartial('getCartInfo');
    }

    public function actionViewCart()
    {
        $cart_info = Yii::$app->CartInfo->getCartInfo();
        return $this->render('viewCart', ['cart_info' => $cart_info]);
    }

    public function actionUpdateCart()
    {
        if (Yii::$app->request->post()) {
            $post_data = Yii::$app->request->post();
            $update_cart = $post_data['update_cart'];
            $product_cart = Yii::$app->session->get('product_cart');
            foreach ($update_cart as $id => $quantity) {
                foreach ($product_cart as $key => $item) {
                    if ($id == $item['product_id']) {
                        $product_cart[$key]['product_quantity'] = $quantity;
                    }
                }
            }
            Yii::$app->session->set('product_cart', $product_cart);
            return $this->redirect('index.php?r=cart/view-cart');
        }
        else
            return $this->goHome();
    }

    public function actionRemoveFromCart()
    {
        $json = array();
        if (Yii::$app->request->post()) {
            $post_data = Yii::$app->request->post();
            if (isset($post_data['product_id'])) {
                $product_id = $post_data['product_id'];
            } else {
                $product_id = 0;
            }
            if ($product_id == 0) {
                $json['error'] = Yii::t('app', 'RemoveCartMsg01');
            } else {
                $total_price = 0;
                $total_product = 0;
                $product_cart = Yii::$app->session->get('product_cart');
                foreach ($product_cart as $key => $item) {
                    if (($item['product_id'] == $product_id)) {
                        unset($product_cart[$key]);
                    } else {
                        $product_price = Product::find()->select(['price'])->where(['id' => $item['product_id']])->one();
                        $product_offer = Yii::$app->CommonFunction->getProductOffer($item['product_id']);
                        $total_price += Yii::$app->CommonFunction->getProductPrice($product_price['price'], $product_offer) * $item['product_quantity'];
                        $total_product += $item['product_quantity'];
                    }
                }
                Yii::$app->session->set('product_cart', $product_cart);
                $json['success'] = Yii::t('app', 'RemoveCartMsg02');
                $json['total'] = ($total_product) . " Sản phẩm - " . number_format($total_price) . " VND";
            }
        } else {
            return $this->goHome();
        }

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return json_encode($json);

    }
}