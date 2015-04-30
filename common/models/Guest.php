<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "guest".
 *
 * @property string $id
 * @property string $full_name
 * @property string $email
 * @property string $phone_number
 * @property string $customer_id
 *
 * @property Customer $customer
 * @property Order[] $orders
 * @property ShoppingCart[] $shoppingCarts
 */
class Guest extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'guest';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['full_name', 'email', 'phone_number'], 'required'],
            [['customer_id'], 'integer'],
            [['email'],'email'],
            [['full_name', 'email'], 'string', 'max' => 255],
            [['phone_number'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'full_name' => 'Họ và tên',
            'email' => 'Email',
            'phone_number' => 'Số điện thoại',
            'customer_id' => 'Customer ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShoppingCarts()
    {
        return $this->hasMany(ShoppingCart::className(), ['guest_id' => 'id']);
    }
}
