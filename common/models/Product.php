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
 * @property string $quantity
 * @property string $sold
 * @property double $tax
 * @property double $fee
 * @property string $tag
 * @property integer $active
 * @property string $category_id
 * @property string $session_id
 * @property string $unit_id
 *
 * @property Image[] $images
 * @property Offer[] $offers
 * @property OrderDetail[] $orderDetails
 * @property Category $category
 * @property Session $session
 * @property Unit $unit
 * @property ProductRating[] $productRatings
 * @property Rating[] $ratings
 * @property ShoppingCart[] $shoppingCarts
 * @property WishList[] $wishLists
 */
class Product extends \yii\db\ActiveRecord
{
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
            [['barcode', 'name', 'price', 'description', 'quantity', 'sold', 'tax', 'active', 'category_id', 'session_id', 'unit_id'], 'required'],
            [['price', 'tax', 'fee'], 'number'],
            [['description'], 'string'],
            [['quantity', 'sold', 'active', 'category_id', 'session_id', 'unit_id'], 'integer'],
            [['barcode'], 'string', 'max' => 20],
            [['name'], 'string', 'max' => 255],
            [['tag'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'barcode' => 'Barcode',
            'name' => 'Name',
            'price' => 'Price',
            'description' => 'Description',
            'quantity' => 'Quantity',
            'sold' => 'Sold',
            'tax' => 'Tax',
            'fee' => 'Fee',
            'tag' => 'Tag',
            'active' => 'Active',
            'category_id' => 'Category ID',
            'session_id' => 'Session ID',
            'unit_id' => 'Unit ID',
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
        return $this->hasMany(OrderDetail::className(), ['product_id' => 'id']);
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
    public function getSession()
    {
        return $this->hasOne(Session::className(), ['id' => 'session_id']);
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
    public function getShoppingCarts()
    {
        return $this->hasMany(ShoppingCart::className(), ['product_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWishLists()
    {
        return $this->hasMany(WishList::className(), ['product_id' => 'id']);
    }
}
