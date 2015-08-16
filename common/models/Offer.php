<?php

namespace common\models;

use backend\components\ParserDateTime;
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
            [['start_date', 'end_date'], 'safe'],
            [['description'], 'string', 'max' => 255],
            [['end_date'], 'validateDate'],
            [['discount'], 'integer', 'min' => 0, 'max' => 50, 'tooSmall' => Yii::t('app', 'Discount must be positive number'), 'tooBig' => Yii::t('app', 'Discount must be lest than or equal 50')],
        ];
    }

    public function validateDate($attribute) {
        if (!empty($this->start_date) && !empty($this->end_date)) {
            if (ParserDateTime::parseToTimestamp($this->start_date) > ParserDateTime::parseToTimestamp($this->end_date)) {
                $this->addError($attribute, Yii::t('app', 'Offer Start Date must greater than Offer End Date'));
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'product_id' => Yii::t('app', 'Product'),
            'discount' => Yii::t('app', 'OfferDiscount'),
            'description' => Yii::t('app', 'OfferDescription'),
            'start_date' => Yii::t('app', 'Offer Start Date'),
            'end_date' => Yii::t('app', 'Offer End Date'),
            'active' => Yii::t('app', 'OfferActive'),
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
