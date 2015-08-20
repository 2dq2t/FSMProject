<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "tmp_product".
 *
 * @property integer $id
 * @property string $last_used
 * @property integer $status
 */
class TmpProduct extends \yii\db\ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tmp_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['last_used'], 'safe'],
            [['status'], 'required'],
            [['last_used', 'status'], 'integer']
        ];
    }
}
