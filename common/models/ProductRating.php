<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_rating".
 *
 * @property string $product_id
 * @property string $rating_id
 * @property string $session_id
 *
 * @property Product $product
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
            [['product_id', 'rating_id'], 'required'],
            [['product_id', 'rating_id'], 'integer'],
            [['session_id'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_id' => 'Product ID',
            'rating_id' => 'Rating ID',
            'session_id' => 'Session ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRating()
    {
        return $this->hasOne(Rating::className(), ['id' => 'rating_id']);
    }
}
