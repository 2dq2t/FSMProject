<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "order_view".
 *
 * @property string $order_id
 * @property string $order_date
 * @property string $receiving_date
 * @property string $full_name
 * @property string $email
 * @property string $phone_number
 * @property integer $order_status_id
 * @property string $address
 */
class OrderView extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_view';
    }

//    public static function primaryKey(){
//        return ['order_id'];
//    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'order_date', 'receiving_date', 'order_status_id'], 'integer'],
            [['order_date', 'receiving_date', 'full_name', 'email', 'phone_number', 'address'], 'required'],
            [['address'], 'string'],
            [['full_name', 'email'], 'string', 'max' => 255],
            [['phone_number'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => Yii::t('app', 'Order ID'),
            'order_date' => Yii::t('app', 'Order Date'),
            'receiving_date' => Yii::t('app', 'Receiving Date'),
            'full_name' => Yii::t('app', 'Full Name'),
            'email' => Yii::t('app', 'Email'),
            'phone_number' => Yii::t('app', 'Phone Number'),
            'order_status_id' => Yii::t('app', 'Order Status ID'),
            'address' => Yii::t('app', 'Address'),
        ];
    }
}
