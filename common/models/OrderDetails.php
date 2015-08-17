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
    private $product_image;
    private $product_unit;
    private $product_price;
    private $product_total;


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

    /**
     * @return mixed
     */
    public function getProductImage()
    {
        return $this->product_image;
    }

    /**
     * @param mixed $product_image
     */
    public function setProductImage($product_image)
    {
        $this->product_image = $product_image;
    }

    /**
     * @return mixed
     */
    public function getProductTotal()
    {
        return $this->product_total;
    }

    /**
     * @param mixed $product_total
     */
    public function setProductTotal($product_total)
    {
        $this->product_total = $product_total;
    }

    /**
     * @return mixed
     */
    public function getProductUnit()
    {
        return $this->product_unit;
    }

    /**
     * @param mixed $product_unit
     */
    public function setProductUnit($product_unit)
    {
        $this->product_unit = $product_unit;
    }

    /**
     * @return mixed
     */
    public function getProductPrice()
    {
        return $this->product_price;
    }

    /**
     * @param mixed $product_price
     */
    public function setProductPrice($product_price)
    {
        $this->product_price = $product_price;
    }
}
