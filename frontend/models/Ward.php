<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "ward".
 *
 * @property integer $Id
 * @property string $Name
 * @property integer $District_Id
 *
 * @property Address[] $addresses
 * @property District $district
 */
class Ward extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ward';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Name', 'District_Id'], 'required'],
            [['District_Id'], 'integer'],
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
            'District_Id' => 'District  ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddresses()
    {
        return $this->hasMany(Address::className(), ['Ward_Id' => 'Id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistrict()
    {
        return $this->hasOne(District::className(), ['Id' => 'District_Id']);
    }

    //function get data from Ward Table
    public static function getOptionsByWard($ward_id){
        $data = static::find()->where(['District_Id'=>$ward_id])->select(['Id as id','Name as name'])->asArray()->all();
        $value = (count($data) == 0) ? ['' => ''] : $data;

        return $value;
    }
}
