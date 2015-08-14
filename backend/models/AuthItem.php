<?php

namespace backend\models;

use backend\components\ParserDateTime;
use Yii;
use yii\base\Exception;
use yii\helpers\Json;
use yii\rbac\DbManager;
use yii\rbac\Item;
use yii\web\Application;

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
 * @property Item $item
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthRule $ruleName
 * @property AuthItemChild[] $authItemChildren
 */
class AuthItem extends \yii\db\ActiveRecord
{
    /**
     * @var Item
     */
    private $_item;

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
            [['name'], 'check'],
            [['rule_name'], 'in',
                'range' => array_keys(Yii::$app->authManager->getRules()),
                'message' => Yii::t('app', 'Rule not exists')],
            [['oldName'], 'safe'],
            [['items'], 'safe']
        ];
    }

    public function check($attribute, $params)
    {
        $authManager = Yii::$app->authManager;
        if ((strlen($this->oldName) == 0 || $this->oldName != $this->name) && ($authManager->getRole($this->name) !== null || $authManager->getPermission($this->name) !== null)) {
            $this->addError($attribute, Yii::t('yii', 'Permission name "{value}" has already been taken.', ['attribute' => $attribute, 'value' => $this->name]));
            $this->errors = Yii::t('yii', 'Permission name "{value}" has already been taken.', ['attribute' => $attribute, 'value' => $this->name]);
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
            'updated_at' => Yii::t('app', 'Updated At')
        ];
    }

    /**
     * Check if is new record.
     * @return boolean
     */
    public function getIsNewRecord()
    {
        return empty($this->oldAttributes);
    }

    /**
     * Find role
     * @param string $id
     * @return null|\self
     */
    public static function findRole($id)
    {
        $item = Yii::$app->authManager->getRole($id);
        if ($item !== null) {
            return new self($item);
        }

        return null;
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
            $this->errorMessage .= $e->getMessage();
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
//            Yii::$app->getAuthManager()->update($this->oldName, $item);
            $dbManager = new DbManager();
            $dbManager->update($this->oldName, $item);
            if ($item->name !== $this->oldName) {
                Yii::$app->db->createCommand()
                    ->update($dbManager->itemChildTable, ['parent' => $item->name], ['parent' => $this->oldName])
                    ->execute();
                Yii::$app->db->createCommand()
                    ->update($dbManager->itemChildTable, ['child' => $item->name], ['child' => $this->oldName])
                    ->execute();
                Yii::$app->db->createCommand()
                    ->update($dbManager->assignmentTable, ['item_name' => $item->name], ['item_name' => $this->oldName])
                    ->execute();
            }

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
                    $this->errorMessage .= Yii::t('app', "Item <strong>{value}</strong> is not assigned:", [
                            'value' => $value,
                        ])
                        . " " . $ex->getMessage() . "<br />";
                    return false;
                }
            }
        } catch (Exception $e){
            $this->errorMessage .= $e->getMessage();
            return false;
        }

        return true;
    }

    public function deleteItem()
    {
        try{
            $dbManager = new DbManager();

            Yii::$app->db->createCommand()
                ->delete($dbManager->itemChildTable, ['or', '[[parent]]=:name', '[[child]]=:name'], [':name' => $this->name])
                ->execute();
            Yii::$app->db->createCommand()
                ->delete($dbManager->assignmentTable, ['item_name' => $this->name])
                ->execute();

            Yii::$app->db->createCommand()
                ->delete($dbManager->itemTable, ['name' => $this->name])
                ->execute();
        } catch (Exception $e) {
            $this->errorMessage .= $e->getMessage();
            return false;
        }
        return true;
    }

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

    public function setItems($items = []) {
        $this->items = $items;
    }
}
