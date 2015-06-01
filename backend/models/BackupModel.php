<?php

namespace backend\models;


use yii\db\ActiveRecord;

/**
 * Backup
 *
 * Yii module to backup, restore databse
 *
 * @version 1.0
 * @author Shiv Charan Panjeta <shiv@toxsl.com> <shivcharan.panjeta@gmail.com>
 */
/**
 * UploadForm class.
 * UploadForm is the data structure for keeping
 */
class Backup extends Model
{
    public $id ;
    public $name ;
    public $size ;
    public $create_time ;
    public $modified_time ;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id','name','size','create_time','modified_time'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => \Yii::t('app', 'File Name'),
            'size' => \Yii::t('app', 'File Size'),
            'create_time' => \Yii::t('app', 'Create Time'),
            'modified_time'=> \Yii::t('app', 'Modified Time'),
        ];
    }


}