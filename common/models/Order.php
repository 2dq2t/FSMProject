<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property string $id
 * @property string $order_date
 * @property string $receiving_date
 * @property double $shipping_fee
 * @property double $discount
 * @property double $tax_amount
 * @property double $net_amount
 * @property string $description
 * @property string $user_id
 * @property string $voucher_id
 * @property string $address_id
 * @property string $order_status_id
 *
 * @property Address $address
 * @property Guest $user
 * @property Voucher $voucher
 * @property OrderStatus $orderStatus
 * @property OrderDetail[] $orderDetails
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
            [['order_date', 'receiving_date', 'shipping_fee', 'discount', 'tax_amount', 'net_amount', 'description', 'user_id', 'address_id', 'order_status_id'], 'required'],
            [['order_date', 'receiving_date'], 'safe'],
            [['shipping_fee', 'discount', 'tax_amount', 'net_amount'], 'number'],
            [['description'], 'string'],
            [['user_id', 'voucher_id', 'address_id', 'order_status_id'], 'integer']
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
            'discount' => Yii::t('app', 'Discount'),
            'tax_amount' => Yii::t('app', 'Tax Amount'),
            'net_amount' => Yii::t('app', 'Net Amount'),
            'description' => Yii::t('app', 'Description'),
            'user_id' => Yii::t('app', 'User ID'),
            'voucher_id' => Yii::t('app', 'Voucher ID'),
            'address_id' => Yii::t('app', 'Address ID'),
            'order_status_id' => Yii::t('app', 'Order Status ID'),
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
    public function getUser()
    {
        return $this->hasOne(Guest::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVoucher()
    {
        return $this->hasOne(Voucher::className(), ['id' => 'voucher_id']);
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
        return $this->hasMany(OrderDetail::className(), ['order_id' => 'id']);
    }
}
