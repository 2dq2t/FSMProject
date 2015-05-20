<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "voucher".
 *
 * @property string $id
 * @property string $name
 * @property string $code
 * @property string $discount
 * @property integer $start_date
 * @property integer $end_date
 * @property integer $active
 * @property string $order_id
 *
 * @property Order $order
 */
class Voucher extends \yii\db\ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'voucher';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'code', 'discount', 'start_date', 'end_date', 'order_id'], 'required'],
            [['discount', 'start_date', 'end_date', 'active', 'order_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'code' => Yii::t('app', 'Code'),
            'discount' => Yii::t('app', 'Discount'),
            'start_date' => Yii::t('app', 'Start Date'),
            'end_date' => Yii::t('app', 'End Date'),
            'active' => Yii::t('app', 'Active'),
            'order_id' => Yii::t('app', 'Order ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
}
