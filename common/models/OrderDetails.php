<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_details".
 *
 * @property string $product_id
 * @property string $order_id
 * @property double $sell_price
 * @property string $quantity
 * @property double $discount
 *
 * @property Order $order
 * @property Product $product
 */
class OrderDetails extends \yii\db\ActiveRecord
{
    public $product_image;
    public $product_unit;
    public $product_price;
    public $product_total;
    public $max_quantity;
    public $tax;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'order_id', 'sell_price', 'quantity'], 'required'],
            [['product_id', 'order_id', 'quantity'], 'integer'],
            [['sell_price', 'discount'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_id' => Yii::t('app', 'Product'),
            'order_id' => Yii::t('app', 'Order ID'),
            'sell_price' => Yii::t('app', 'Sell Price'),
            'quantity' => Yii::t('app', 'Quantity'),
            'discount' => Yii::t('app', 'Discount'),
        ];
    }
}
