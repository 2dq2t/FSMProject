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
 * @property string $tax_amount
 * @property double $net_amount
 * @property string $description
 * @property string $guest_id
 * @property integer $order_status_id
 * @property string $order_address_id
 *
 * @property Guest $guest
 * @property OrderAddress $orderAddress
 * @property OrderStatus $orderStatus
 * @property OrderDetails[] $orderDetails
 * @property Product[] $products
 * @property Voucher[] $vouchers
 */
class Order extends \yii\db\ActiveRecord
{
    private $voucher;
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
            [['order_date', 'receiving_date', 'shipping_fee', 'tax_amount', 'net_amount', 'description', 'guest_id', 'order_status_id', 'order_address_id'], 'required'],
            [['tax_amount', 'guest_id', 'order_status_id', 'order_address_id'], 'integer'],
            [['shipping_fee', 'net_amount'], 'number'],
            [['description'], 'string', 'max' => 255],
            [['voucher', 'order_date', 'receiving_date'], 'safe']
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
            'order_address_id' => Yii::t('app', 'Order Address ID'),
        ];
    }

    public function getVoucher(){
        return $this->voucher;
    }

    public function setVoucher($voucher) {
        $this->voucher = $voucher;
    }
}
