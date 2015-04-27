<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $full_name
 * @property string $email
 * @property string $phone_number
 * @property integer $useraccount_id
 *
 * @property Order[] $orders
 * @property Useraccount $useraccount
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
            [['full_name', 'email', 'phone_number'], 'required'],
            [['email'], 'email'],
            [['useraccount_id'], 'integer'],
            [['full_name', 'email'], 'string', 'max' => 100],
            [['phone_number'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'full_name' => 'Họ và tên',
            'email' => 'Email',
            'phone_number' => 'Số điện thoại',
            'useraccount_id' => 'Useraccount ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUseraccount()
    {
        return $this->hasOne(Useraccount::className(), ['id' => 'useraccount_id']);
    }
}
