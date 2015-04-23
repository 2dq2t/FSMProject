<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "rating".
 *
 * @property integer $Id
 * @property double $Rating
 * @property string $Description
 *
 * @property Productrating[] $productratings
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
            [['Rating'], 'number'],
            [['Description'], 'required'],
            [['Description'], 'string', 'max' => 100],
            [['Rating'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'Rating' => 'Rating',
            'Description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductratings()
    {
        return $this->hasMany(Productrating::className(), ['RatingId' => 'Id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['Id' => 'ProductId'])->viaTable('productrating', ['RatingId' => 'Id']);
    }
}
