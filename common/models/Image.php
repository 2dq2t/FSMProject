<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "image".
 *
 * @property string $id
 * @property string $name
 * @property string $path
 * @property string $resize_path
 * @property string $product_id
 *
 * @property Product $product
 */
class Image extends \yii\db\ActiveRecord
{
    public $product_image = [];
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
            [['name', 'path', 'resize_path', 'product_id'], 'required'],
            [['product_id'], 'integer'],
            [['name', 'path', 'resize_path'], 'string', 'max' => 255],
            [['product_image'], 'file', 'skipOnEmpty'=> true, 'maxFiles' => 10, 'extensions' => 'jpeg, jpg, png, gif']
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
            'path' => Yii::t('app', 'Path'),
            'resize_path' => Yii::t('app', 'Resize Path'),
            'product_id' => Yii::t('app', 'Product ID'),
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
