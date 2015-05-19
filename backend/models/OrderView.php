<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "order_view".
 *
 * @property string $id
 * @property string $customer_name
 * @property string $customer_phone_number
 * @property string $order_date
 * @property string $receiving_date
 * @property double $shipping_fee
 * @property string $tax_amount
 * @property double $net_amount
 * @property string $description
 * @property string $order_status
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
        return ['id'];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'order_date', 'receiving_date', 'tax_amount'], 'integer'],
            [['customer_name', 'customer_phone_number', 'order_date', 'receiving_date', 'shipping_fee', 'tax_amount', 'net_amount', 'description', 'order_status'], 'required'],
            [['shipping_fee', 'net_amount'], 'number'],
            [['customer_name', 'description', 'order_status'], 'string', 'max' => 255],
            [['customer_phone_number'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'customer_name' => Yii::t('app', 'Customer Name'),
            'customer_phone_number' => Yii::t('app', 'Customer Phone Number'),
            'order_date' => Yii::t('app', 'Order Date'),
            'receiving_date' => Yii::t('app', 'Receiving Date'),
            'shipping_fee' => Yii::t('app', 'Shipping Fee'),
            'tax_amount' => Yii::t('app', 'Tax Amount'),
            'net_amount' => Yii::t('app', 'Net Amount'),
            'description' => Yii::t('app', 'Description'),
            'order_status' => Yii::t('app', 'Order Status'),
        ];
    }
}
