<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_season".
 *
 * @property string $product_id
 * @property string $season_id
 *
 * @property Product $product
 * @property Season $season
 */
class ProductSeason extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_season';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'season_id'], 'required'],
            [['product_id', 'season_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_id' => Yii::t('app', 'Product ID'),
            'season_id' => Yii::t('app', 'Season ID'),
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
    public function getSeason()
    {
        return $this->hasOne(Season::className(), ['id' => 'season_id']);
    }
}
