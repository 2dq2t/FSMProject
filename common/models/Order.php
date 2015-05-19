<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use backend\models\OrderStatus;

/**
 * This is the model class for table "order".
 *
 * @property string $id
 * @property string $order_date
 * @property string $receiving_date
 * @property double $shipping_fee
 * @property string $tax_amount
 * @property double $net_amount
 * @property string $description
 * @property string $guest_id
 * @property integer $order_status_id
 * @property string $address_id
 *
 * @property Address $address
 * @property Guest $guest
 * @property \backend\models\OrderStatus $orderStatus
 * @property OrderDetails[] $orderDetails
 * @property Product[] $products
 * @property Voucher[] $vouchers
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_date', 'receiving_date', 'shipping_fee', 'tax_amount', 'net_amount', 'description', 'guest_id', 'order_status_id', 'address_id'], 'required'],
            [['tax_amount', 'guest_id', 'order_status_id', 'address_id'], 'integer'],
            [['order_date', 'receiving_date'], 'safe'],
            [['shipping_fee', 'net_amount'], 'number'],
            [['description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'order_date' => Yii::t('app', 'Order Date'),
            'receiving_date' => Yii::t('app', 'Receiving Date'),
            'shipping_fee' => Yii::t('app', 'Shipping Fee'),
            'tax_amount' => Yii::t('app', 'Tax Amount'),
            'net_amount' => Yii::t('app', 'Net Amount'),
            'description' => Yii::t('app', 'Description'),
            'guest_id' => Yii::t('app', 'Guest ID'),
            'order_status_id' => Yii::t('app', 'Order Status ID'),
            'address_id' => Yii::t('app', 'Address ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddress()
    {
        return $this->hasOne(Address::className(), ['id' => 'address_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGuest()
    {
        return $this->hasOne(Guest::className(), ['id' => 'guest_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderStatus()
    {
        return $this->hasOne(OrderStatus::className(), ['id' => 'order_status_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderDetails()
    {
        return $this->hasMany(OrderDetails::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->viaTable('order_details', ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVouchers()
    {
        return $this->hasMany(Voucher::className(), ['order_id' => 'id']);
    }
}
