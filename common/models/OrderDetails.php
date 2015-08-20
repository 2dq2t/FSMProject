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
    private $productImage;
    private $productUnit;
    private $productPrice;
    private $productTotal;


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
        return $this->productImage;
    }

    /**
     * @param mixed $product_image
     */
    public function setProductImage($product_image)
    {
        $this->productImage = $product_image;
    }

    /**
     * @return mixed
     */
    public function getProductTotal()
    {
        return $this->productTotal;
    }

    /**
     * @param mixed $product_total
     */
    public function setProductTotal($product_total)
    {
        $this->productTotal = $product_total;
    }

    /**
     * @return mixed
     */
    public function getProductUnit()
    {
        return $this->productUnit;
    }

    /**
     * @param mixed $product_unit
     */
    public function setProductUnit($product_unit)
    {
        $this->productUnit = $product_unit;
    }

    /**
     * @return mixed
     */
    public function getProductPrice()
    {
        return $this->productPrice;
    }

    /**
     * @param mixed $product_price
     */
    public function setProductPrice($product_price)
    {
        $this->productPrice = $product_price;
    }
}
