<?php

namespace common\models;

use yii\base\NotSupportedException;
use backend\components\ParserDateTime;
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
 * @property integer $dob
 * @property string $gender
 * @property string $auth_key
 * @property string $password_reset_token
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 * @property string $guest_id
 * @property string $address_id
 *
 * @property Address $address
 * @property Guest $guest
 * @property ProductRating[] $productRatings
 * @property WishList[] $wishLists
 * @property Product[] $products
 */
class Customer extends ActiveRecord implements IdentityInterface
{
    public $re_password;
    public $new_password;
    public $re_new_password;
    public $full_name;

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
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
            [['username', 'password'], 'required'],
            ['re_password', 'required', 'on' => 'addCustomer'],
            [['username', 'password', 'created_at', 'address_id'], 'required', 'on' => 'adminCreate'],
            [['new_password', 're_new_password'], 'required','on' => 'changePassword' ],
            [['dob', 'created_at', 'updated_at'], 'safe'],
            [['created_at', 'updated_at', 'status', 'guest_id', 'address_id'], 'integer'],
            [['gender'], 'string'],
            [['username', 'avatar'], 'string', 'max' => 255, 'min' => 6],
            [['password'], 'string', 'max' => 255, 'min' => 6],
            [['password'], 'match', 'pattern' => '/^(?=.*[a-zA-Z]+.*)[0-9a-zA-Z]{6,}$/', 'message' => Yii::t('app', 'Password must contain at least one letter and be longer than six characters.')],
            [['new_password'], 'string', 'max' => 255, 'min' => 6],
            ['re_password', 'compare', 'compareAttribute' => 'password'],
            ['re_new_password', 'compare', 'compareAttribute' => 'new_password'],
            [['auth_key', 'password_reset_token'], 'string', 'max' => 255],
            [['username'], 'unique'],
            [['avatar'], 'file', 'extensions' => 'jpeg, jpg, png, gif']
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['adminEdit'] = ['username', 'update_at', 'address_id'];//Scenario Values Only Accepted
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Customer Username'),
            'password' => Yii::t('app', 'Customer Password'),
            'new_password' => Yii::t('app', 'Customer New Password'),
            're_password' => Yii::t('app', 'Customer Confirm Password'),
            're_new_password' => Yii::t('app', 'Customer Re_Confirm Password'),
            'avatar' => Yii::t('app', 'Customer Avatar'),
            'dob' => Yii::t('app', 'Customer Date of birth'),
            'gender' => Yii::t('app', 'Customer Gender'),
            'auth_key' => Yii::t('app', 'Customer Auth Key'),
            'password_reset_token' => Yii::t('app', 'Customer Password Reset Token'),
            'created_at' => Yii::t('app', 'Customer Created At'),
            'updated_at' => Yii::t('app', 'Customer Updated At'),
            'status' => Yii::t('app', 'Customer Status'),
            'guest_id' => Yii::t('app', 'Guest ID'),
            'address_id' => Yii::t('app', 'Address ID'),
        ];
    }

    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * @return mixed
     */
    public function getRePassword()
    {
        return $this->re_password;
    }

    /**
     * @param mixed $re_password
     */
    public function setRePassword($re_password)
    {
        $this->re_password = $re_password;
    }

    /**
     * @return mixed
     */
    public function getNewPassword()
    {
        return $this->new_password;
    }

    /**
     * @param mixed $new_password
     */
    public function setNewPassword($new_password)
    {
        $this->new_password = $new_password;
    }

    /**
     * @return mixed
     */
    public function getReNewPassword()
    {
        return $this->re_new_password;
    }

    /**
     * @param mixed $re_new_password
     */
    public function setReNewPassword($re_new_password)
    {
        $this->re_new_password = $re_new_password;
    }

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->full_name;
    }

    /**
     * @param mixed $full_name
     */
    public function setFullName($full_name)
    {
        $this->full_name = $full_name;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
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
            'status' => self::STATUS_ACTIVE,
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
        return static::find()->select(['customer.id', 'customer.username', 'guest.full_name AS full_name'])->join('inner join','guest','customer.guest_id = guest.id')->where('customer.status = "'.self::STATUS_ACTIVE.'" and customer.id = "'.$id.'"')->one();
    }
}