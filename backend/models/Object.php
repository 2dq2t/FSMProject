<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "object".
 *
 * @property string $id
 * @property string $name
 * @property string $object_class
 * @property string $object_table_name
 *
 * @property DataExport[] $dataExports
 * @property Employee[] $employees
 * @property DataImport[] $dataImports
 */
class Object extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'object';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name', 'object_class', 'object_table_name'], 'required'],
            [['id'], 'integer'],
            [['name', 'object_class', 'object_table_name'], 'string', 'max' => 255]
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
            'object_class' => Yii::t('app', 'Object Class'),
            'object_table_name' => Yii::t('app', 'Object Table Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDataExports()
    {
        return $this->hasMany(DataExport::className(), ['object_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees()
    {
        return $this->hasMany(Employee::className(), ['id' => 'employee_id'])->viaTable('data_import', ['object_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDataImports()
    {
        return $this->hasMany(DataImport::className(), ['object_id' => 'id']);
    }
}
