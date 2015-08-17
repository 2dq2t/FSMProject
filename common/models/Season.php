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
    private $products_list;

    /**
     * @return mixed
     */
    public function getProductsList()
    {
        return $this->products_list;
    }

    /**
     * @param mixed $products_list
     */
    public function setProductsList($products_list)
    {
        $this->products_list = $products_list;
    }
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
            [['from', 'to', 'products_list'], 'safe'],
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
            'name' => Yii::t('app', 'SeasonName'),
            'from' => Yii::t('app', 'SeasonFrom'),
            'to' => Yii::t('app', 'SeasonTo'),
            'active' => Yii::t('app', 'SeasonActive'),
        ];
    }
}
