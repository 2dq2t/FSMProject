<?php

namespace common\models;

use backend\components\ParserDateTime;
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
    private $productsList;

    /**
     * @return mixed
     */
    public function getProductsList()
    {
        return $this->productsList;
    }

    /**
     * @param mixed $products_list
     */
    public function setProductsList($products_list)
    {
        $this->productsList = $products_list;
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
            [['from', 'to', 'productsList'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['to'], 'validateDate']
        ];
    }

    public function validateDate($attribute) {
        if (!empty($this->from) && !empty($this->to)) {
            if (ParserDateTime::parseToTimestamp($this->from) > ParserDateTime::parseToTimestamp($this->to)) {
                $this->addError($attribute, Yii::t('app', 'Season from date must greater than Season to date'));
            }
        }
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
