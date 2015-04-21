<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property integer $Id
 * @property string $Detail
 * @property integer $Ward_Id
 *
 * @property Ward $ward
 * @property Order[] $orders
 * @property Staff[] $staff
 * @property Useraccount[] $useraccounts
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Detail', 'Ward_Id'], 'required'],
            [['Ward_Id'], 'integer'],
            [['Detail'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'Detail' => 'Detail',
            'Ward_Id' => 'Ward  ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWard()
    {
        return $this->hasOne(Ward::className(), ['Id' => 'Ward_Id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['AddressId' => 'Id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaff()
    {
        return $this->hasMany(Staff::className(), ['AddressId' => 'Id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUseraccounts()
    {
        return $this->hasMany(Useraccount::className(), ['AddressId' => 'Id']);
    }
}
