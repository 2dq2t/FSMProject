<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $Id
 * @property string $FullName
 * @property string $Email
 * @property string $PhoneNumber
 * @property integer $UserAccountId
 *
 * @property Order[] $orders
 * @property UserAccount $userAccount
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['FullName', 'Email', 'PhoneNumber'], 'required', 'message'=>'{attribute} không được để trống'],
            [['UserAccountId'], 'integer'],
            [['Email'], 'email','message' => 'Email không hợp lệ'],
            [['FullName', 'Email'], 'string', 'max' => 100],
            [['PhoneNumber'], 'string', 'max' => 11]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'FullName' => 'Họ và tên',
            'Email' => 'Email',
            'PhoneNumber' => 'Số điện thoại',
            'UserAccountId' => 'User Account ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['UserId' => 'Id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAccount()
    {
        return $this->hasOne(UserAccount::className(), ['Id' => 'UserAccountId']);
    }
}
