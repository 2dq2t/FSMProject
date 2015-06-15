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
            'username' => Yii::t('app', 'Tên Đăng Nhập'),
            'password' => Yii::t('app', 'Mật Khẩu'),
            'new_password' => Yii::t('app', 'Mật Khẩu Mới'),
            're_password' => Yii::t('app', 'Xác Nhận Mật Khẩu'),
            're_new_password' => Yii::t('app', 'Xác Nhận Mật Khẩu Mới'),
            'avatar' => Yii::t('app', 'Avatar'),
            'dob' => Yii::t('app', 'Date of birth'),
            'gender' => Yii::t('app', 'Gender'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'status' => Yii::t('app', 'Status'),
            'guest_id' => Yii::t('app', 'Guest ID'),
            'address_id' => Yii::t('app', 'Address ID'),
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
    public function getGuest()
    {
        return $this->hasOne(Guest::className(), ['id' => 'guest_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductRatings()
    {
        return $this->hasMany(ProductRating::className(), ['customer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWishLists()
    {
        return $this->hasMany(WishList::className(), ['customer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->viaTable('wish_list', ['customer_id' => 'id']);
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
            $customer->created_at = time();
            $customer->guest_id = $this->guest_id;
            if ($customer->save()) {
                return $customer;
            }
        }
        return null;
    }

    public function UpdateCustomer($customerId){
        if($this->validate()){
            $customer = Customer::findOne($customerId);
            $customer->username = $this->username;
            $customer->dob = strtotime($this->dob);
            $customer->gender = $this->gender;
            $customer->updated_at = time();
            $customer->guest_id = $this->guest_id;
            $customer->avatar = $this->avatar;
            if ($customer->save()) {
                return $customer;
            }
        }
        return null;
    }

    public function ChangePassword($customerId){
        if($this->validate()){
            $customer = Customer::findOne($customerId);
            $customer->setPassword($this->new_password);

            if($customer->save()){
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