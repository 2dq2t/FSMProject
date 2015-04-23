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
            [['UserName', 'Password', 'Gender', 'AddressId'], 'required','message'=>'{attribute} không được để trống'],
            [['DOB', 'CreatedAt', 'UpdatedAt'], 'safe'],
            [['Gender', 'Status'], 'string'],
            [['AddressId'], 'integer'],
            [['UserName', 'OrderList'], 'string', 'max' => 45, 'min' => 6, 'tooShort' => '{attribute} phải có ít nhất 6 kí tự'],
            [['Password'], 'string', 'max' => 100],
            [['RePassword'], 'string', 'max' => 20],
            ['RePassword', 'compare', 'compareAttribute' => 'Password','message' => '{attribute} không khớp'],
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
            'RePassword' => 'Xác Nhận Mật Khẩu',
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

    public function setPassword($password)
    {
        $this->Password = Yii::$app->security->generatePasswordHash($password);
    }

    public function register(){

        if($this->validate()){
            $userAccount = new Useraccount();
            $userAccount->UserName = $this->UserName;
            $userAccount->setPassword($this->Password);
            $userAccount->DOB = $this->DOB;
            $userAccount->Gender = $this->Gender;
            $userAccount->PasswordResetToken = "DEFAULT VALUE";
            $time = new \DateTime('now', new \DateTimeZone('Asia/Ho_Chi_Minh'));
            $userAccount->CreatedAt = $time->format('Y-m-d H:i:s');
            $userAccount->Status = "Active";
            $userAccount->AddressId = $this->AddressId;
            if ($userAccount->save()) {
                return $userAccount;
            }
        }
        return null;
    }
}
