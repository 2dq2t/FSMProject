<?php

namespace backend\models;

use Yii;
use yii\base\Model;


class UploadBackup extends Model
{
    public $upload_file;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['upload_file'], 'required', 'on' => 'restore'],
            [['upload_file'], 'file', 'extensions' => 'sql']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'upload_file' => \Yii::t('app', 'Upload file'),
        ];
    }
}
