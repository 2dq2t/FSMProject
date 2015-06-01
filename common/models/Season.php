<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "season".
 *
 * @property string $id
 * @property string $name
 * @property integer $from
 * @property integer $to
 * @property integer $active
 *
 * @property ProductSeason[] $productSeasons
 * @property Product[] $products
 */
class Season extends \yii\db\ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'season';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'from', 'to'], 'required'],
            [['active'], 'integer'],
            [['from', 'to'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'from' => Yii::t('app', 'From'),
            'to' => Yii::t('app', 'To'),
            'active' => Yii::t('app', 'Active'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductSeasons()
    {
        return $this->hasMany(ProductSeason::className(), ['season_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->viaTable('product_season', ['season_id' => 'id']);
    }
}
