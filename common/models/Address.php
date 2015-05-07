<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property string $id
 * @property string $detail
 * @property string $ward_id
 *
 * @property Ward $ward
 * @property Customer[] $customers
 * @property Order[] $orders
 * @property Staff[] $staff
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
            [['detail', 'ward_id'], 'required'],
            [['ward_id'], 'integer'],
            [['detail'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'detail' => 'Địa chỉ',
            'ward_id' => 'Xã / Phường',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWard()
    {
        return $this->hasOne(Ward::className(), ['id' => 'ward_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomers()
    {
        return $this->hasMany(Customer::className(), ['address_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['address_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStaff()
    {
        return $this->hasMany(Staff::className(), ['address_id' => 'id']);
    }
}
