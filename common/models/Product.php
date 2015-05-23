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
 * @property string $tag
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
 * @property Rating[] $ratings
 * @property ProductSeason[] $productSeasons
 * @property Season[] $seasons
 * @property WishList[] $wishLists
 * @property Customer[] $customers
 */
class Product extends \yii\db\ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    public $product_seasons = [];
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
            [['barcode', 'name', 'price', 'description', 'intro', 'quantity_in_stock', 'tag', 'create_date', 'category_id', 'unit_id'], 'required'],
            [['barcode', 'quantity_in_stock', 'sold', 'tax', 'create_date', 'active', 'category_id', 'unit_id'], 'integer'],
            [['price'], 'number'],
            [['description', 'intro'], 'string'],
            [['name', 'tag'], 'string', 'max' => 255],
            [['sold'], 'default','value'=>'0'],
            [['tax'], 'default','value'=>'0'],
            [['quantity_in_stock', 'tax', 'price', 'sold'], 'number','min' => 0],
            [['product_seasons'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'barcode' => Yii::t('app', 'Barcode'),
            'name' => Yii::t('app', 'Name'),
            'price' => Yii::t('app', 'Price'),
            'description' => Yii::t('app', 'Description'),
            'intro' => Yii::t('app', 'Intro'),
            'quantity_in_stock' => Yii::t('app', 'Quantity In Stock'),
            'sold' => Yii::t('app', 'Sold'),
            'tax' => Yii::t('app', 'Tax'),
            'tag' => Yii::t('app', 'Tag'),
            'create_date' => Yii::t('app', 'Create Date'),
            'active' => Yii::t('app', 'Active'),
            'category_id' => Yii::t('app', 'Category ID'),
            'unit_id' => Yii::t('app', 'Unit ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(Image::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffers()
    {
        return $this->hasMany(Offer::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderDetails()
    {
        return $this->hasMany(OrderDetails::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['id' => 'order_id'])->viaTable('order_details', ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnit()
    {
        return $this->hasOne(Unit::className(), ['id' => 'unit_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductRatings()
    {
        return $this->hasMany(ProductRating::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRatings()
    {
        return $this->hasMany(Rating::className(), ['id' => 'rating_id'])->viaTable('product_rating', ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductSeasons()
    {
        return $this->hasMany(ProductSeason::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeasons()
    {
        return $this->hasMany(Season::className(), ['id' => 'season_id'])->viaTable('product_season', ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWishLists()
    {
        return $this->hasMany(WishList::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomers()
    {
        return $this->hasMany(Customer::className(), ['id' => 'customer_id'])->viaTable('wish_list', ['product_id' => 'id']);
    }
}
