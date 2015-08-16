<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_address".
 *
 * @property string $id
 * @property string $detail
 * @property string $district_id
 *
 * @property Order[] $orders
 * @property District $district
 */
class OrderAddress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_address';
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
            'detail' => Yii::t('app', 'Detail'),
            'district_id' => Yii::t('app', 'District'),
        ];
    }
}
