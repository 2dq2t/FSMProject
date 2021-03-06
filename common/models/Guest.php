<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "guest".
 *
 * @property string $id
 * @property string $full_name
 * @property string $email
 * @property string $phone_number
 *
 * @property Customer[] $customers
 * @property Order[] $orders
 */
class Guest extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'guest';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['full_name', 'email', 'phone_number'], 'required'],
            [['phone_number'], 'integer'],
            [['phone_number'], 'match', 'pattern' => '/^[0](\d{3})(\d{3})(\d{3,4})$/', 'message' => Yii::t('app','PhoneNumber Error')],
            [['full_name', 'email'], 'string', 'max' => 255],
            [['email'],'email'],
//            [['phone_number'], 'string', 'max' => 15, 'min' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'full_name' => Yii::t('app', 'Full Name'),
            'email' => Yii::t('app', 'Email'),
            'phone_number' => Yii::t('app', 'Phone Number'),
        ];
    }
}
