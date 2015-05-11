<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "voucher_type".
 *
 * @property string $id
 * @property string $type
 * @property string $amount
 *
 * @property Voucher[] $vouchers
 */
class VoucherType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'voucher_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'amount'], 'required'],
            [['type', 'amount'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'amount' => 'Amount',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVouchers()
    {
        return $this->hasMany(Voucher::className(), ['voucher_type_id' => 'id']);
    }
}
