<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rating".
 *
 * @property integer $id
 * @property double $rating
 * @property string $description
 *
 * @property ProductRating[] $productRatings
 * @property Product[] $products
 */
class Rating extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rating';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rating'], 'number'],
            [['description'], 'required'],
            [['description'], 'string', 'max' => 100],
            [['rating'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'rating' => Yii::t('app', 'Rating'),
            'description' => Yii::t('app', 'RatingDescription'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductRatings()
    {
        return $this->hasMany(ProductRating::className(), ['rating_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->viaTable('product_rating', ['rating_id' => 'id']);
    }
}
