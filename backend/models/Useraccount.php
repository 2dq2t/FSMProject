<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "useraccount".
 *
 * @property integer $id
 * @property integer $username
 * @property string $password
 * @property string $avatar
 * @property string $dob
 * @property string $gender
 * @property string $order_list
 * @property string $password_reset_token
 * @property string $created_at
 * @property string $updated_at
 * @property integer $status
 * @property integer $address_id
 *
 * @property User[] $users
 * @property Address $address
 * @property WishList[] $wishLists
 */
class Useraccount extends \yii\db\ActiveRecord
{
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
            [['username', 'password', 'gender', 'created_at', 'address_id'], 'required'],
            [['username', 'status', 'address_id'], 'integer'],
            [['dob', 'created_at', 'updated_at'], 'safe'],
            [['gender'], 'string'],
            [['password'], 'string', 'max' => 20],
            [['avatar'], 'string', 'max' => 60],
            [['order_list'], 'string', 'max' => 45],
            [['password_reset_token'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'avatar' => 'Avatar',
            'dob' => 'Dob',
            'gender' => 'Gender',
            'order_list' => 'Order List',
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
}
