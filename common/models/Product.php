<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property string $id
 * @property string $barcode
 * @property string $name
 * @property double $price
 * @property string $description
 * @property string $intro
 * @property string $quantity_in_stock
 * @property string $sold
 * @property string $tax
 * @property string $create_date
 * @property integer $active
 * @property integer $category_id
 * @property string $unit_id
 *
 * @property Image[] $images
 * @property Offer[] $offers
 * @property OrderDetails[] $orderDetails
 * @property Order[] $orders
 * @property Category $category
 * @property Unit $unit
 * @property ProductRating[] $productRatings
 * @property ProductSeason[] $productSeasons
 * @property Season[] $seasons
 * @property ProductTag[] $productTags
 * @property Tag[] $tags
 * @property SlideShow[] $slideShows
 * @property WishList[] $wishLists
 * @property Customer[] $customers
 */
class Product extends \yii\db\ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    public $product_seasons;
    public $product_tags;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['barcode', 'name', 'price', 'description', 'intro', 'quantity_in_stock', 'create_date', 'category_id', 'unit_id'], 'required'],
            [['barcode', 'quantity_in_stock', 'sold', 'tax', 'create_date', 'active', 'category_id', 'unit_id'], 'integer'],
            [['price'], 'number'],
            [['description', 'intro'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['sold'], 'default','value'=>'0'],
            [['tax'], 'default','value'=>'0'],
            [['quantity_in_stock', 'tax', 'price', 'sold'], 'number','min' => 0],
            [['product_seasons', 'product_tags'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'barcode' => Yii::t('app', 'ProductBarcode'),
            'name' => Yii::t('app', 'ProductName'),
            'price' => Yii::t('app', 'ProductsPrice'),
            'description' => Yii::t('app', 'ProductDescription'),
            'intro' => Yii::t('app', 'ProductIntro'),
            'quantity_in_stock' => Yii::t('app', 'Product Quantity In Stock'),
            'sold' => Yii::t('app', 'ProductSold'),
            'tax' => Yii::t('app', 'ProductTax'),
            'create_date' => Yii::t('app', 'Product Create Date'),
            'active' => Yii::t('app', 'ProductActive'),
            'category_id' => Yii::t('app', 'Category ID'),
            'unit_id' => Yii::t('app', 'Unit ID'),
        ];
    }
}
