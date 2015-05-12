<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "staff".
 *
 * @property string $id
 * @property string $full_name
 * @property string $password
 * @property string $dob
 * @property string $gender
 * @property string $phone_number
 * @property string $email
 * @property string $image
 * @property string $start_date
 * @property string $address_id
 * @property integer $status
 *
 * @property Address $address
 */
class Staff extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'staff';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['full_name', 'password', 'dob', 'gender', 'phone_number', 'email', 'image', 'start_date', 'address_id', 'status'], 'required', 'on' => 'adminCreate'],
            [['full_name', 'dob', 'gender', 'phone_number', 'email', 'start_date', 'address_id', 'status'], 'required', 'on' => 'adminEdit'],
            [['dob', 'start_date'], 'safe'],
            [['dob', 'start_date'], 'date', 'format' => 'yyyy-mm-dd', 'message' => Yii::t('app', '{attribute}: is not a date!')],
            [['gender'], 'string'],
            [['address_id', 'status'], 'integer'],
            [['full_name'], 'string', 'max' => 255],
            [['password', 'email'], 'string', 'max' => 255],
            [['phone_number'], 'string', 'max' => 15],
            [['image'], 'string', 'max' => 255],
            [['email'], 'unique'],
            [['image'], 'file', 'extensions' => 'jpeg, jpg, png, gif'],
        ];
    }

//    public function scenarios()
//    {
//        $scenarios = parent::scenarios();
//        $scenarios['adminEdit'] = ['full_name', 'dob', 'gender', 'phone_number', 'email', 'start_date', 'address_id'. 'status'];//Scenario Values Only Accepted
//        return $scenarios;
//    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'full_name' => Yii::t('app', 'Full Name'),
            'password' => Yii::t('app', 'Password'),
            'dob' => Yii::t('app', 'Dob'),
            'gender' => Yii::t('app', 'Gender'),
            'phone_number' => Yii::t('app', 'Phone Number'),
            'email' => Yii::t('app', 'Email'),
            'image' => Yii::t('app', 'Image'),
            'start_date' => Yii::t('app', 'Start Date'),
            'address_id' => Yii::t('app', 'Address ID'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddress()
    {
        return $this->hasOne(Address::className(), ['id' => 'address_id']);
    }
}
