<?php
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 01/08/2015
 * Time: 5:24 CH
 */

namespace frontend\models;

use Yii;
use yii\base\Model;

class CheckoutInfo extends Model
{
    public $note;
    public $receiving_date;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['receiving_date'], 'required'],
            [['note'], 'string'],
            [['receiving_date'], 'save'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'note' => Yii::t('app','Checkout Note'),
            'receiving_date' => Yii::t('app','Checkout ReceivingDate'),
        ];
    }
    public function getNote()
    {
        return $this->$note;
    }

    public function getReceivingDate(){

        return $this->receiving_date;
    }

    public function save(){
        return null;
    }

}