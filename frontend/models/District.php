<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "district".
 *
 * @property integer $Id
 * @property string $Name
 * @property integer $City_Id
 *
 * @property City $city
 * @property Ward[] $wards
 */
class District extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'district';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Name', 'City_Id'], 'required'],
            [['City_Id'], 'integer'],
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
            'City_Id' => 'City  ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['Id' => 'City_Id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWards()
    {
        return $this->hasMany(Ward::className(), ['District_Id' => 'Id']);
    }

    public static function getDistrict($city_id) {
        $data= City::find()
            ->where(['City_Id'=>$city_id])
            ->select(['Id','Name'])->asArray()->all();

        return $data;
    }

    //function get data from District Table
    public static function getOptionsByDistrict($district_id){
        $data = static::find()->where(['City_Id'=>$district_id])->select(['Id as id','Name as name'])->asArray()->all();
        $value = (count($data) == 0) ? ['' => ''] : $data;

        return $value;
    }
}
