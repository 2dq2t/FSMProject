<?php

namespace common\models;

use Yii;
use yii\validators\NumberValidator;

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
    private $productImage = [];
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
            [['barcode'], 'match', 'pattern' => '/^[0-9]{4}$/', 'message' => Yii::t('app', 'Product barcode must be exactly 4 number')],
            [['description', 'intro'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['sold'], 'default','value'=>'0'],
            [['tax'], 'default','value'=>'0'],
            [['quantity_in_stock', 'tax', 'price', 'sold', 'barcode'], 'number','min' => 0],
            [['product_seasons', 'product_tags'], 'safe'],
            [['productImage'], 'file', 'skipOnEmpty'=> true, 'maxFiles' => 10, 'extensions' => 'jpeg, jpg, png, gif']
        ];
    }

    /*
     * This is the function to validate Barcode follow format EAN13
    */
    public function validateBarcode($attribute, $params)
    {
        $barcode = substr($this->$attribute, 0, strlen($this->$attribute)-1);
        // sum each of odd number digits
        $odd_sum = 0;
        // sum each of even number digits
        $even_sum = 0;

        for($i = 0; $i < strlen($barcode); $i++) {
//            if (!is_int($barcode[$i])) {
//                $this->addError($attribute, Yii::t('app', 'Invalid barcode format.'));
//            }
            if ($i % 2 == 0) {
                // 1. sum each of the odd numbered digits
                // 2. multiply result by three
                $odd_sum += $barcode[$i] * 3;
            } else {
                // 3. sum of each of the even numbered digits
                $even_sum += $barcode[$i];
            }
        }

        // 4. subtract the result from the next highest power of 10
        $checkSum = (ceil(($odd_sum + $even_sum)/10))*10 - ($odd_sum + $even_sum);
        var_dump(strlen($this->$attribute) - 1);

        // if the check digit and the last digit of the barcode are OK return true;
        if($checkSum != substr($this->$attribute, -1)) {
            $this->addError($attribute, Yii::t('app', 'Invalid barcode.'));
        }
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

    public function getProductImage()
    {
        return $this->productImage;
    }

    public function setProductImage(array $product_image)
    {
        $this->productImage = $product_image;
    }
}
