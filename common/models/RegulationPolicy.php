<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "regulation_policy".
 *
 * @property integer $id
 * @property string $title
 * @property string $alias
 * @property string $post_info
 * @property string $full_post
 * @property string $image
 * @property integer $active
 */
class RegulationPolicy extends \yii\db\ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'regulation_policy';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'alias', 'post_info', 'full_post'], 'required'],
            [['post_info', 'full_post'], 'string'],
            [['active'], 'integer'],
            [['image'], 'required', 'on' => 'adminCreate'],
            [['title', 'alias', 'image'], 'string', 'max' => 255],
            [['image'], 'file', 'skipOnEmpty'=> true, 'maxFiles' => 10, 'extensions' => 'jpeg, jpg, png, gif'],
            [['title', 'alias', 'post_info', 'full_post', 'image', 'active'], 'required'],
            [['post_info', 'full_post'], 'string'],
            [['active'], 'integer'],
            [['title', 'alias', 'image'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'alias' => Yii::t('app', 'Alias'),
            'post_info' => Yii::t('app', 'Post Info'),
            'full_post' => Yii::t('app', 'Full Post'),
            'image' => Yii::t('app', 'Image'),
            'active' => Yii::t('app', 'Active'),
        ];
    }
}
