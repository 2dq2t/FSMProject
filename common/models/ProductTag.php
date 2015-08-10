<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product_tag".
 *
 * @property string $tag_id
 * @property string $product_id
 *
 * @property Product $product
 * @property Tag $tag
 */
class ProductTag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_tag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag_id', 'product_id'], 'required'],
            [['tag_id', 'product_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tag_id' => Yii::t('app', 'Tag ID'),
            'product_id' => Yii::t('app', 'Product ID'),
        ];
    }
}
