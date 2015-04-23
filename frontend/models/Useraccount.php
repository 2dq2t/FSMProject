<?php

namespace frontend\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

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
class UserAccount extends ActiveRecord implements IdentityInterface
{
    public $RePassword;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%useraccount}}';
    }
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
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


    public function setPassword($password)
    {
        $this->Password = Yii::$app->security->generatePasswordHash($password);
    }

    public function register(){

        if($this->validate()){
            $userAccount = new UserAccount();
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
    public function getUserAccount()
    {
        return $this->hasOne(UserAccount::className(), ['Id' => 'UserAccountId']);
    }
    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['UserName' => $username, 'Status' => 'Active']);
    }
    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->Password);
    }
    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'PasswordResetToken' => $token,
            'Status' => 'Active',
        ]);
    }
    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->AuthKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
    public function generateAuthKey()
    {
        $this->AuthKey = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->PasswordResetToken = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->PasswordResetToken = null;
    }
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'Status' => 'Active']);
    }
}
