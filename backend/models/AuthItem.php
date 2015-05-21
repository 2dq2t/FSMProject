<?php

namespace backend\models;

use Yii;
use yii\rbac\Item;

/**
 * This is the model class for table "auth_item".
 *
 * @property string $name
 * @property integer $type
 * @property string $description
 * @property string $rule_name
 * @property string $data
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthRule $ruleName
 * @property AuthItemChild[] $authItemChildren
 */
class AuthItem extends \yii\db\ActiveRecord
{
    public $oldname;
    public $children = [];
    private $errorMessage = '';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['description', 'data'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64],
            [['name'], 'check'],
            [['oldname', 'children'], 'safe']
        ];
    }

    public function check($attribute, $params)
    {
        if (((strlen($this->oldname) == 0) || ($this->oldname != $this->name)) &&
            ((\Yii::$app->getAuthManager()->getRole($this->$attribute) !== null) ||
                \Yii::$app->getAuthManager()->getPermission($this->$attribute) !== null)) {
            $this->addError($attribute, 'Duplicate Item "'.$this->$attribute.'"');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Name'),
            'type' => Yii::t('app', 'Type'),
            'description' => Yii::t('app', 'Description'),
            'rule_name' => Yii::t('app', 'Rule Name'),
            'data' => Yii::t('app', 'Data'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::className(), ['item_name' => 'name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRuleName()
    {
        return $this->hasOne(AuthRule::className(), ['name' => 'rule_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren()
    {
        return $this->hasMany(AuthItemChild::className(), ['child' => 'name']);
    }

    public function createItem()
    {
        $item = new Item(
            [
                'name' => $this->name,
                'type' => $this->type,
                'description' => $this->description,
                'ruleName' => trim($this->rule_name) ? trim($this->rule_name) : null,
            ]
        );
        Yii::$app->getAuthManager()->add($item);
        foreach ($this->children as $value) {

            try {
                Yii::$app->getAuthManager()->addChild($item, new Item(['name' => $value]));
            } catch (\Exception $ex) {
                $this->errorMessage .= Yii::t('app', "Item <strong>{value}</strong> is not assigned:", [
                        'value' => $value,
                    ])
                    . " " . $ex->getMessage() . "<br />";
            }
        }
        return $item;
    }

    public function updateItem()
    {
        $item = new Item();
        $item->name = $this->name;
        $item->type = $this->type;
        $item->description = $this->description;
        $item->ruleName = trim($this->rule_name) ? trim($this->rule_name) : null;
        Yii::$app->getAuthManager()->update($this->oldname, $item);
        $children = Yii::$app->getAuthManager()->getChildren($item->name);
        foreach ($children as $value) {
            $key = array_search($value->name, $this->children);
            if ($key === false) {
                Yii::$app->getAuthManager()->removeChild($item, $value);
            } else {
                unset($this->children[$key]);
            }
        }
        foreach ($this->children as $value) {
            try {
                Yii::$app->getAuthManager()->addChild($item, new Item(['name' => $value]));
            } catch (\Exception $ex) {
                $this->errorMessage .= Yii::t('app', "Item <strong>{value}</strong> is not assigned:", [
                        'value' => $value,
                    ])
                    . " " . $ex->getMessage() . "<br />";
            }
        }
        return $item;
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
}
