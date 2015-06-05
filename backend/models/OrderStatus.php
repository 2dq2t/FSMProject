<?php

namespace backend\models;

use common\models\Order;
use Yii;

/**
 * This is the model class for table "order_status".
 *
 * @property integer $id
 * @property string $name
 * @property string $comment
 *
 * @property \common\models\Order[] $orders
 */
class OrderStatus extends \yii\db\ActiveRecord
{
    const PENDING_CONFIRM = 1;
    const CONFIRM_ORDER = 2;
    const CANCEL_ORDER = 3;
    const DELIVERED_ORDER = 4;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['comment'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique']
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
            'comment' => Yii::t('app', 'Comment'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['order_status_id' => 'id']);
    }
}
