<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "slide_show".
 *
 * @property string $id
 * @property string $path
 * @property string $title
 * @property string $description
 * @property integer $active
 * @property string $product_id
 *
 * @property Product $product
 */
class SlideShow extends \yii\db\ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    public $image = '';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'slide_show';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['path', 'title', 'description'], 'required'],
            [['active', 'product_id'], 'integer'],
            [['path', 'title', 'description'], 'string', 'max' => 255],
            [['image'], 'file', 'extensions' => 'jpeg, jpg, png, gif'],
            [['image'], 'required', 'on' => 'adminCreate']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'path' => Yii::t('app', 'Path'),
            'title' => Yii::t('app', 'SlideShowTitle'),
            'description' => Yii::t('app', 'SlideShowDescription'),
            'active' => Yii::t('app', 'SlideShowActive'),
            'product_id' => Yii::t('app', 'Product'),
        ];
    }
}
