<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "order_view".
 *
 * @property string $order_id
 * @property string $customer_name
 * @property string $customer_phone_no
 * @property string $product_name
 * @property string $quantity
 * @property double $sell_price
 * @property double $discount
 * @property integer $order_status_id
 */
class OrderView extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_view';
    }

    public static function primaryKey(){
        return ['order_id'];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'quantity', 'order_status_id'], 'integer'],
            [['customer_name', 'customer_phone_no', 'product_name', 'quantity', 'sell_price'], 'required'],
            [['sell_price', 'discount'], 'number'],
            [['customer_name', 'product_name'], 'string', 'max' => 255],
            [['customer_phone_no'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => Yii::t('app', 'Order ID'),
            'customer_name' => Yii::t('app', 'Customer Name'),
            'customer_phone_no' => Yii::t('app', 'Customer Phone No'),
            'product_name' => Yii::t('app', 'Product Name'),
            'quantity' => Yii::t('app', 'Quantity'),
            'sell_price' => Yii::t('app', 'Sell Price'),
            'discount' => Yii::t('app', 'Discount'),
            'order_status_id' => Yii::t('app', 'Order Status ID'),
        ];
    }
}
