<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "useraccount".
 *
 * @property integer $Id
 * @property string $UserName
 * @property string $Password
 * @property string $Avatar
 * @property string $DOB
 * @property string $Gender
 * @property string $OrderList
 * @property string $AuthKey
 * @property string $PasswordResetToken
 * @property string $CreatedAt
 * @property string $UpdatedAt
 * @property string $Status
 * @property integer $AddressId
 *
 * @property User[] $users
 * @property Address $address
 * @property Wishlist[] $wishlists
 */
class Useraccount extends \yii\db\ActiveRecord
{
    public $RePassword;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'useraccount';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['UserName', 'Password', 'Gender', 'PasswordResetToken', 'CreatedAt', 'AddressId'], 'required','message'=>'{attribute} không được để trống'],
            [['DOB', 'CreatedAt', 'UpdatedAt'], 'safe'],
            [['UserName'], 'unique','message' => 'Tên đăng nhập đã tồn tại'],
            [['Gender', 'Status'], 'string'],
            [['AddressId'], 'integer'],
            [['UserName', 'OrderList'], 'string', 'max' => 45],
            [['Password'], 'string', 'max' => 20],
            [['RePassword'], 'string', 'max' => 20],
            ['RePassword', 'compare', 'compareAttribute' => 'Password','message' => 'Xác nhận mật khẩu không khớp'],
            [['Avatar'], 'string', 'max' => 60],
            [['AuthKey', 'PasswordResetToken'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'UserName' => 'Tên Đăng Nhập',
            'Password' => 'Mật Khẩu',
            'RePassword' => 'Mật Khẩu',
            'Avatar' => 'Avatar',
            'DOB' => 'Dob',
            'Gender' => 'Giới tính',
            'OrderList' => 'Order List',
            'AuthKey' => 'Auth Key',
            'UpdatedAt' => 'Updated At',
            'AddressId' => 'Địa chỉ',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['UserAccountId' => 'Id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddress()
    {
        return $this->hasOne(Address::className(), ['Id' => 'AddressId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWishlists()
    {
        return $this->hasMany(Wishlist::className(), ['UserAccount_Id' => 'Id']);
    }
}
