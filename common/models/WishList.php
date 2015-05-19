<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "wish_list".
 *
 * @property string $customer_id
 * @property string $product_id
 *
 * @property Customer $customer
 * @property Product $product
 */
class WishList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'wish_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'product_id'], 'required'],
            [['customer_id', 'product_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_id' => Yii::t('app', 'Customer ID'),
            'product_id' => Yii::t('app', 'Product ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }
}
