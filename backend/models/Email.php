<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "email".
 *
 * @property string $id
 * @property string $subject
 * @property string $message
 * @property integer $create_at
 * @property string $employee_id
 *
 * @property Employee $employee
 */
class Email extends \yii\db\ActiveRecord
{
    private $customer = [];
    private $product = [];

    /**
     * @return array
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param array $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

    /**
     * @return array
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param array $customer
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'email';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subject', 'message', 'create_at', 'employee_id'], 'required'],
            [['message'], 'string'],
            [['create_at', 'employee_id'], 'integer'],
            [['subject'], 'string', 'max' => 255],
            [['customer', 'product'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'subject' => Yii::t('app', 'Subject'),
            'message' => Yii::t('app', 'Send mail Message'),
            'create_at' => Yii::t('app', 'Create At'),
            'employee_id' => Yii::t('app', 'Employee ID'),
            'product' => Yii::t('app', 'Product email'),
            'customer' => Yii::t('app', 'Customer email')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['id' => 'employee_id']);
    }

}
