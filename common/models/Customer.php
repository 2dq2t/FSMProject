<?php

namespace common\models;

use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use Yii;

/**
 * This is the model class for table "customer".
 *
 * @property string $id
 * @property string $username
 * @property string $password
 * @property string $avatar
 * @property string $dob
 * @property string $gender
 * @property string $auth_key
 * @property string $password_reset_token
 * @property string $created_at
 * @property string $updated_at
 * @property integer $status
 * @property string $address_id
 *
 * @property Address $address
 * @property Guest[] $guests
 * @property WishList[] $wishLists
 */
class Customer extends ActiveRecord implements IdentityInterface
{
    public $re_password;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'gender', 'address_id'], 'required'],
            [['dob', 'created_at', 'updated_at'], 'safe'],
            [['gender'], 'string'],
            [['status', 'address_id'], 'integer'],
            [['username', 'avatar'], 'string', 'max' => 255, 'min' => '6', 'tooShort' => '{attribute} phải có ít nhất 6 kí tự'],
            [['password'], 'string', 'max' => 255, 'min' => 8, 'tooShort' => '{attribute} phải có ít nhất 8 kí tự'],
            ['re_password', 'compare', 'compareAttribute' => 'password','message' => '{attribute} không khớp'],
            [['auth_key', 'password_reset_token'], 'string', 'max' => 32],
            [['username'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Tên đăng nhập',
            'password' => 'Mật khẩu',
            're_password' => 'Xác nhận Mật khẩu',
            'avatar' => 'Avatar',
            'dob' => 'Ngày sinh',
            'gender' => 'Giới tính',
            'auth_key' => 'Auth Key',
            'password_reset_token' => 'Password Reset Token',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
            'address_id' => 'Address ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddress()
    {
        return $this->hasOne(Address::className(), ['id' => 'address_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGuests()
    {
        return $this->hasMany(Guest::className(), ['customer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWishLists()
    {
        return $this->hasMany(WishList::className(), ['useraccount_id' => 'id']);
    }

    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    public function register(){
        if($this->validate()){
            $customer = new Customer();
            $customer->username = $this->username;
            $customer->setPassword($this->password);
            $customer->dob = $this->dob;
            $customer->gender = $this->gender;
            $time = new \DateTime('now', new \DateTimeZone('Asia/Ho_Chi_Minh'));
            $customer->created_at = $time->format('Y-m-d H:i:s');
            $customer->address_id = $this->address_id;
            if ($customer->save()) {
                return $customer;
            }
        }
        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'Status' => 1]);
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
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
            'password_reset_token' => $token,
            'status' => 1,
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
        return $this->auth_key;
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
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => 1]);
    }
}