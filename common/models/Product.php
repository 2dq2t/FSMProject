<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property string $barcode
 * @property string $name
 * @property double $price
 * @property double $old_price
 * @property string $description
 * @property string $total
 * @property string $sold
 * @property double $tax
 * @property double $fee
 * @property integer $active
 * @property integer $category_id
 *
 * @property Image[] $images
 * @property Offer[] $offers
 * @property OrderDetail[] $orderDetails
 * @property Category $category
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
            [['barcode', 'name', 'price', 'description', 'total', 'sold', 'tax', 'active', 'category_id'], 'required'],
            [['price', 'old_price', 'tax', 'fee'], 'number'],
            [['description'], 'string'],
            [['total', 'sold', 'active', 'category_id'], 'integer'],
            [['barcode'], 'string', 'max' => 20],
            [['name'], 'string', 'max' => 100]
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
            'old_price' => 'Old Price',
            'description' => 'Description',
            'total' => 'Total',
            'sold' => 'Sold',
            'tax' => 'Tax',
            'fee' => 'Fee',
            'active' => 'Active',
            'category_id' => 'Category ID',
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
        return $this->hasMany(OrderDetail::className(), ['product_d' => 'id']);
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
