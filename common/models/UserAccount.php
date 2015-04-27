<?php

namespace common\models;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use Yii;

/**
 * This is the model class for table "useraccount".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $avatar
 * @property string $dob
 * @property string $gender
 * @property string $order_list
 * @property string $password_reset_token
 * @property string $authkey
 * @property string $created_at
 * @property string $updated_at
 * @property integer $status
 * @property integer $address_id
 *
 * @property User[] $users
 * @property Address $address
 * @property WishList[] $wishLists
 */
class UserAccount extends ActiveRecord implements IdentityInterface
{
    public $repassword;
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
            [['username', 'password', 'gender', 'address_id'], 'required'],
            [['status', 'address_id'], 'integer'],
            [['dob', 'created_at', 'updated_at'], 'safe'],
            [['gender'], 'string'],
            [['username'], 'string', 'max' => 50, 'min' => 6, 'tooShort' => '{attribute} phải có ít nhất 6 kí tự'],
            [['password'], 'string', 'max' => 255, 'min' => 6, 'tooShort' => '{attribute} phải có ít nhất 6 kí tự'],
            [['repassword'], 'string', 'max' => 255, 'min' => 6, 'tooShort' => '{attribute} phải có ít nhất 6 kí tự'],
            ['repassword', 'compare', 'compareAttribute' => 'password','message' => '{attribute} không khớp'],
            [['avatar'], 'string', 'max' => 60],
            [['order_list'], 'string', 'max' => 45],
            [['authkey','password_reset_token'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Tên Đăng Nhập',
            'gender' => 'Giới tính',
            'password' => 'Mật Khẩu',
            'repassword' => 'Mật Khẩu',
            'order_list' => 'Order List',
            'password_reset_token' => 'Password Reset Token',
            'created_at' => 'Created At',
            'authkey' => 'Auth Key',
            'updated_at' => 'Updated At',
            'status' => 'Status',
            'address_id' => 'Xã / Phường',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['useraccount_id' => 'id']);
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
            $userAccount = new UserAccount();
            $userAccount->username = $this->username;
            $userAccount->setPassword($this->password);
            $userAccount->dob = $this->dob;
            $userAccount->gender = $this->gender;
            $time = new \DateTime('now', new \DateTimeZone('Asia/Ho_Chi_Minh'));
            $userAccount->created_at = $time->format('Y-m-d H:i:s');
            $userAccount->address_id = $this->address_id;
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
        return $this->authkey;
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
        $this->authkey = Yii::$app->security->generateRandomString();
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
        return static::findOne(['id' => $id, 'status' => 1]);
    }
}
