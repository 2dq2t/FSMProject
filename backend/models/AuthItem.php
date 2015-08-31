<?php

namespace backend\models;

use backend\components\ParserDateTime;
use Yii;
use yii\base\Exception;
use yii\rbac\Item;

/**
 * This is the model class for table "auth_item".
 *
 * @property string $name
 * @property integer $type
 * @property string $description
 * @property string $data
 * @property string $rule_name
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property AuthAssignment[] $authAssignments
 * @property Employee[] $users
 * @property AuthItemChild[] $authItemChildren
 */
class AuthItem extends \yii\db\ActiveRecord
{
    /**
     * This hold the old name of item
     * @var string
     */
    private $oldName;
    /**
     * @var $error
     */
    private $items = [];

    private $errorMessage;
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
            [['items', 'oldName'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => $this->type == Item::TYPE_PERMISSION ? Yii::t('app', 'Permission Name') : Yii::t('app', 'Role Name'),
            'type' => Yii::t('app', 'Type'),
            'description' => Yii::t('app', 'Description'),
            'data' => Yii::t('app', 'Data'),
            'rule_name' => Yii::t('app', 'Rule Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @var Item $item
     * @return boolean
     */

    public function createItem()
    {
        $item = new Item(
            [
                'name' => $this->name,
                'type' => $this->type,
                'description' => !empty(trim($this->description)) ? trim($this->description) : null,
                'ruleName' => trim($this->rule_name) ? trim($this->rule_name) : null,
                'createdAt' => ParserDateTime::getTimeStamp()
            ]
        );

        try {
            Yii::$app->getAuthManager()->add($item);
            foreach ($this->items as $value) {
                try {
                    Yii::$app->getAuthManager()->addChild($item, new Item(['name' => $value]));
                } catch (\Exception $ex) {
                    $this->errorMessage .= Yii::t('app', "Item <strong>{value}</strong> is not assigned:", [
                            'value' => $value,
                        ])
                        . " " . $ex->getMessage() . "<br />";
                    return false;
                }
            }
        } catch (Exception $e) {
            $this->errorMessage = $e->getMessage();
            return false;
        }

        return true;
    }

    public function updateItem()
    {
        $item = new Item();
        $item->name = $this->name;
        $item->type = $this->type;
        $item->description = !empty(trim($this->description)) ? trim($this->description) : null;
        $item->ruleName = trim($this->rule_name) ? trim($this->rule_name) : null;

        try {
            Yii::$app->getAuthManager()->update($this->oldName, $item);
            $children = Yii::$app->getAuthManager()->getChildren($item->name);

            foreach ($children as $value) {
                $key = array_search($value->name, $this->items);
                if ($key === false) {
                    Yii::$app->getAuthManager()->removeChild($item, $value);
                } else {
                    unset($this->items[$key]);
                }
            }

            foreach ($this->items as $value) {
                try {
                    Yii::$app->getAuthManager()->addChild($item, new Item(['name' => $value]));
                } catch (\Exception $ex) {
                    $this->errorMessage = Yii::t('app', "Item <strong>{value}</strong> is not assigned:", [
                            'value' => $value,
                        ])
                        . " " . $ex->getMessage() . "<br />";
                    return false;
                }
            }
        } catch (Exception $e){
            $this->errorMessage = $e->getMessage();
            return false;
        }

        return true;
    }

//    public function deleteItem()
//    {
//        try{
//            $dbManager = new DbManager();
//
//            Yii::$app->db->createCommand()
//                ->delete($dbManager->itemChildTable, ['or', '[[parent]]=:name', '[[child]]=:name'], [':name' => $this->name])
//                ->execute();
//            Yii::$app->db->createCommand()
//                ->delete($dbManager->assignmentTable, ['item_name' => $this->name])
//                ->execute();
//
//            Yii::$app->db->createCommand()
//                ->delete($dbManager->itemTable, ['name' => $this->name])
//                ->execute();
//        } catch (Exception $e) {
//            $this->errorMessage = $e->getMessage();
//            return false;
//        }
//        return true;
//    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    public function getOldName() {
        return $this->oldName;
    }

    public function setOldName($oldName) {
        $this->oldName = $oldName;
    }

    public function getItems() {
        return $this->items;
    }

    public function setItems($items) {
        $this->items = $items;
    }
}
