<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "offer".
 *
 * @property string $id
 * @property string $product_id
 * @property double $discount
 * @property string $description
 * @property string $start_date
 * @property string $end_date
 * @property integer $active
 *
 * @property Product $product
 */
class Offer extends \yii\db\ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'offer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'discount', 'description', 'start_date', 'end_date'], 'required'],
            [['product_id', 'active'], 'integer'],
            [['price'], 'number'],
            [['start_date', 'end_date'], 'safe'],
            [['description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'discount' => Yii::t('app', 'Discount'),
            'description' => Yii::t('app', 'Description'),
            'start_date' => Yii::t('app', 'Start Date'),
            'end_date' => Yii::t('app', 'End Date'),
            'active' => Yii::t('app', 'Active'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
