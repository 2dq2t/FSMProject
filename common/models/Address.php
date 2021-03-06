<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property string $id
 * @property string $detail
 * @property string $district_id
 *
 * @property District $district
 * @property Customer[] $customers
 * @property \backend\models\Employee[] $employees
 * @property Order[] $orders
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['detail', 'district_id'], 'required'],
            [['district_id'], 'integer'],
            [['detail'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'detail' => Yii::t('app', 'CustomerAddress'),
            'district_id' => Yii::t('app', 'DistrictLabel'),
        ];
    }
}
