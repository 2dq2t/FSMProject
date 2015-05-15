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
 *
 * @property Customer[] $customers
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
            [['phone_number'], 'integer'],
            [['full_name', 'email'], 'string', 'max' => 255],
            [['email'],'email'],
            [['email'], 'unique'],
            [['phone_number'], 'string', 'max' => 15, 'min' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'full_name' => Yii::t('app', 'Full Name'),
            'email' => Yii::t('app', 'Email'),
            'phone_number' => Yii::t('app', 'Phone Number'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomers()
    {
        return $this->hasMany(Customer::className(), ['guest_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['guest_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShoppingCarts()
    {
        return $this->hasMany(ShoppingCart::className(), ['guest_id' => 'id']);
    }
}
