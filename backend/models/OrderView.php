<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "order_view".
 *
 * @property string $order_id
 * @property string $order_date
 * @property string $receiving_date
 * @property double $shipping_fee
 * @property string $description
 * @property string $full_name
 * @property string $email
 * @property string $phone_number
 * @property integer $order_status_id
 * @property string $address
 * @property string $voucher_discount
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
            [['order_id', 'order_date', 'receiving_date', 'order_status_id', 'voucher_discount'], 'integer'],
            [['order_date', 'receiving_date', 'shipping_fee', 'description', 'full_name', 'email', 'phone_number', 'address'], 'required'],
            [['shipping_fee'], 'number'],
            [['address'], 'string'],
            [['description', 'full_name', 'email'], 'string', 'max' => 255],
            [['phone_number'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => Yii::t('app', 'Order ID'),
            'order_date' => Yii::t('app', 'Order Date'),
            'receiving_date' => Yii::t('app', 'Receiving Date'),
            'shipping_fee' => Yii::t('app', 'Shipping Fee'),
            'description' => Yii::t('app', 'Description'),
            'full_name' => Yii::t('app', 'Full Name'),
            'email' => Yii::t('app', 'Email'),
            'phone_number' => Yii::t('app', 'Phone Number'),
            'order_status_id' => Yii::t('app', 'Order Status ID'),
            'address' => Yii::t('app', 'Address'),
            'voucher_discount' => Yii::t('app', 'Voucher Discount'),
        ];
    }
}
