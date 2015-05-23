<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "data_export".
 *
 * @property string $employee_id
 * @property string $object_id
 * @property string $filename
 * @property string $status
 * @property string $update_time
 *
 * @property Employee $employee
 * @property Object $object
 */
class DataExport extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'data_export';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['employee_id', 'object_id', 'update_time'], 'required'],
            [['employee_id', 'object_id', 'update_time'], 'integer'],
            [['status'], 'string'],
            [['filename'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'employee_id' => Yii::t('app', 'Employee ID'),
            'object_id' => Yii::t('app', 'Object ID'),
            'filename' => Yii::t('app', 'Filename'),
            'status' => Yii::t('app', 'Status'),
            'update_time' => Yii::t('app', 'Update Time'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['id' => 'employee_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getObject()
    {
        return $this->hasOne(Object::className(), ['id' => 'object_id']);
    }
}
