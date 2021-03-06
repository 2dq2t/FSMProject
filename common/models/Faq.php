<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "faq".
 *
 * @property string $id
 * @property string $question
 * @property string $answer
 * @property integer $active
 */
class Faq extends \yii\db\ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'faq';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['question', 'answer'], 'required'],
            [['question', 'answer'], 'string'],
            [['active'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'question' => Yii::t('app', 'FAQsQuestion'),
            'answer' => Yii::t('app', 'FAQsAnswer'),
            'active' => Yii::t('app', 'FAQsActive'),
        ];
    }
}
