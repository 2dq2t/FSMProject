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
            [['name', 'code', 'discount', 'start_date', 'end_date'], 'required'],
            [['discount', 'active', 'order_id'], 'integer'],
            [['start_date', 'end_date'], 'safe'],
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
            'name' => Yii::t('app', 'VoucherName'),
            'code' => Yii::t('app', 'VoucherCode'),
            'discount' => Yii::t('app', 'VoucherDiscount'),
            'start_date' => Yii::t('app', 'Voucher Start Date'),
            'end_date' => Yii::t('app', 'Voucher End Date'),
            'active' => Yii::t('app', 'VoucherActive'),
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
