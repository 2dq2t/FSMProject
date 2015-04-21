<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "city".
 *
 * @property integer $Id
 * @property string $Name
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
            [['Name'], 'required'],
            [['Name'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'Name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistricts()
    {
        return $this->hasMany(District::className(), ['City_Id' => 'Id']);
    }

    //function get data from City Table
    public static function getCity(){
        $data = static::find()->all();
        $value = (count($data) == 0)? ['' => '']:\yii\helpers\ArrayHelper::map($data, 'Id', 'Name');

        return $value;
    }
}
