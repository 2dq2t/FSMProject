<?php

namespace common\models;

use nepstor\validators\DateTimeCompareValidator;
use Yii;
use yii\i18n\Formatter;
use yii\validators\DateValidator;

/**
 * This is the model class for table "voucher".
 *
 * @property string $id
 * @property string $name
 * @property string $code
 * @property integer $discount
 * @property string $start_date
 * @property string $end_date
 * @property integer $active
 * @property string $voucher_type_id
 *
 * @property Order[] $orders
 * @property VoucherType $voucherType
 */
class Voucher extends \yii\db\ActiveRecord
{
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
            [['name', 'code', 'discount', 'start_date', 'end_date', 'voucher_type_id'], 'required'],
            [['discount', 'active', 'voucher_type_id'], 'integer'],
            [['start_date', 'end_date'], 'safe'],
            [['name'], 'string', 'max' => 45],
            [['code'], 'string', 'max' => 32],
            ['end_date', 'dateCompare']
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
            'voucher_type_id' => Yii::t('app', 'Voucher Type ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['voucher_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVoucherType()
    {
        return $this->hasOne(VoucherType::className(), ['id' => 'voucher_type_id']);
    }

    public function dateCompare($attribute, $params) {
        $start_date = date($this->start_date);
        $end_date = date($this->$attribute);

        if ($end_date < $start_date) {
            $this->addError('end_date', 'End date must be greater or equal than start date.');
        }
    }
}
