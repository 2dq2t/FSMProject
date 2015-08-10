<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_rating".
 *
 * @property string $product_id
 * @property string $rating_id
 * @property string $customer_id
 *
 * @property Product $product
 * @property Customer $customer
 * @property Rating $rating
 */
class ProductRating extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_rating';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'rating_id', 'customer_id'], 'required'],
            [['product_id', 'rating_id', 'customer_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_id' => Yii::t('app', 'Product ID'),
            'rating_id' => Yii::t('app', 'Rating ID'),
            'customer_id' => Yii::t('app', 'Customer ID'),
        ];
    }
}
