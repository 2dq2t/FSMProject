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
 */
class SlideShow extends \yii\db\ActiveRecord
{
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
            [['active'], 'integer'],
            [['path', 'title'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 255],
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
//            'path' => Yii::t('app', 'Path'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'active' => Yii::t('app', 'Active'),
        ];
    }
}
