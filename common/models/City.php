<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "city".
 *
 * @property string $id
 * @property string $name
 *
 * @property District[] $districts
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'city';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'City'),
            'name' => Yii::t('app', 'Name'),
        ];
    }

    //function get file from City Table
    public static function getCity(){
        $data = static::find()->all();
        $value = (count($data) == 0)? ['' => '']:\yii\helpers\ArrayHelper::map($data, 'id', 'name');

        return $value;
    }
}
