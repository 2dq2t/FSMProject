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
            [['path', 'title'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 150],
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
            'id' => 'ID',
//            'path' => 'Path',
            'image' => 'Image',
            'title' => 'Title',
            'description' => 'Description',
            'active' => 'Active',
        ];
    }
}
