<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "order_details_extend".
 *
 * @property string $name
 * @property string $tax
 * @property double $sell_price
 * @property string $quantity
 * @property double $discount
 * @property string $order_id
 * @property string $order_date
 * @property string $receiving_date
 */
class OrderDetailsExtend extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_details_extend';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'sell_price', 'quantity', 'order_date', 'receiving_date'], 'required'],
            [['tax', 'quantity', 'order_id', 'order_date', 'receiving_date'], 'integer'],
            [['sell_price', 'discount'], 'number'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Name'),
            'tax' => Yii::t('app', 'Tax'),
            'sell_price' => Yii::t('app', 'Sell Price'),
            'quantity' => Yii::t('app', 'Quantity'),
            'discount' => Yii::t('app', 'Discount'),
            'order_id' => Yii::t('app', 'Order ID'),
            'order_date' => Yii::t('app', 'Order Date'),
            'receiving_date' => Yii::t('app', 'Receiving Date'),
        ];
    }
}
