<?php

namespace backend\models;

use common\models\Address;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "employee".
 *
 * @property string $id
 * @property string $full_name
 * @property string $password
 * @property integer $dob
 * @property string $gender
 * @property string $phone_number
 * @property string $email
 * @property string $note
 * @property string $image
 * @property string $auth_key
 * @property string $password_reset_token
 * @property string $start_date
 * @property integer $status
 * @property string $address_id
 *
 * @property Address $address
 */
class Employee extends ActiveRecord implements IdentityInterface
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    private $assignments = [];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'employee';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['full_name', 'password', 'dob', 'gender', 'phone_number', 'email', 'image', 'start_date', 'address_id'], 'required', 'on' => 'adminCreate'],
            [['full_name', 'dob', 'gender', 'phone_number', 'email', 'start_date', 'address_id'], 'required', 'on' => 'adminEdit'],
            [['full_name', 'dob', 'gender', 'phone_number', 'email', 'address_id'], 'required', 'on' => 'updateProfile'],
            [['phone_number', 'status', 'address_id'], 'integer'],
            [['dob', 'start_date'], 'safe'],
            [['gender', 'note'], 'string'],
            [['full_name', 'password', 'email', 'image', 'auth_key', 'password_reset_token'], 'string', 'max' => 255],
            [['password'], 'string', 'min' => 6],
            [['email'], 'unique'],
            [['email'], 'email'],
            [['password'], 'match', 'pattern' => '/^(?=.*[a-zA-Z]+.*)[0-9a-zA-Z]{6,}$/', 'message' => Yii::t('app', 'Password must contain at least one letter and be longer than six characters.')],
//            [['phone_number'], 'string', 'max' => 15, 'min' => 10],
            [['assignments'], 'safe'],
            [['phone_number'], 'match', 'pattern' => '/^[0](\d{3})(\d{3})(\d{3,4})$/', 'message' => 'Phone number must have \'0\' in first and contain 10 or 11 digit number.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'full_name' => Yii::t('app', 'Employee Full Name'),
            'password' => Yii::t('app', 'Employee Password'),
            'dob' => Yii::t('app', 'Employee Dob'),
            'gender' => Yii::t('app', 'Employee Gender'),
            'phone_number' => Yii::t('app', 'Employee Phone Number'),
            'email' => Yii::t('app', 'Employee Email'),
            'note' => Yii::t('app','Employee Note'),
            'image' => Yii::t('app', 'Employee Image'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'start_date' => Yii::t('app', 'Employee Start Date'),
            'status' => Yii::t('app', 'Employee Status'),
            'address_id' => Yii::t('app', 'Address ID'),
        ];
    }

    public function getAssignments(){
        return $this->assignments;
    }

    public function setAssignments($assignments) {
        $this->assignments = $assignments;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException(Yii::t('app', '"findIdentityByAccessToken" is not implemented.'));
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByUsername($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
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
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
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
}
