<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "image".
 *
 * @property integer $Id
 * @property string $Name
 * @property string $Path
 * @property integer $ProductId
 *
 * @property Product $product
 */
class Image extends \yii\db\ActiveRecord
{
    public $productImage = [];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Name', 'Path', 'ProductId'], 'required'],
            [['ProductId'], 'integer'],
            [['Name'], 'string', 'max' => 45],
            [['Path'], 'string', 'max' => 100],
            [['productImage'], 'file', 'skipOnEmpty'=> true, 'maxFiles' => 10, 'extensions' => 'jpeg, jpg, png, gif']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'Name' => 'Name',
            'Path' => 'Path',
            'ProductId' => 'Product ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['Id' => 'ProductId']);
    }
}
