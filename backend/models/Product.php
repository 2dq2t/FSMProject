<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property integer $Id
 * @property string $Barcode
 * @property string $Name
 * @property double $SellPrice
 * @property string $Description
 * @property integer $Total
 * @property integer $Sold
 * @property integer $Active
 * @property integer $CategoryId
 *
 * @property Image[] $images
 * @property Offer[] $offers
 * @property Orderdetail[] $orderdetails
 * @property Category $category
 * @property Productrating[] $productratings
 * @property Rating[] $ratings
 * @property Shoppingcart[] $shoppingcarts
 * @property Wishlist[] $wishlists
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
            [['Barcode', 'Name', 'SellPrice', 'Description', 'Total', 'Sold', 'Active', 'CategoryId'], 'required'],
            [['SellPrice'], 'number'],
            [['Description'], 'string'],
            [['Total', 'Sold', 'Active', 'CategoryId'], 'integer'],
            [['Barcode'], 'string', 'max' => 20],
            [['Name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'Barcode' => 'Barcode',
            'Name' => 'Name',
            'SellPrice' => 'Sell Price',
            'Description' => 'Description',
            'Total' => 'Total',
            'Sold' => 'Sold',
            'Active' => 'Active',
            'CategoryId' => 'Category ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(Image::className(), ['ProductId' => 'Id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffers()
    {
        return $this->hasMany(Offer::className(), ['ProductId' => 'Id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderdetails()
    {
        return $this->hasMany(Orderdetail::className(), ['ProductId' => 'Id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['Id' => 'CategoryId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductratings()
    {
        return $this->hasMany(Productrating::className(), ['ProductId' => 'Id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRatings()
    {
        return $this->hasMany(Rating::className(), ['Id' => 'RatingId'])->viaTable('productrating', ['ProductId' => 'Id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShoppingcarts()
    {
        return $this->hasMany(Shoppingcart::className(), ['ProductId' => 'Id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWishlists()
    {
        return $this->hasMany(Wishlist::className(), ['ProductId' => 'Id']);
    }
}
