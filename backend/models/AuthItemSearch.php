<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\AuthItem;
use yii\data\ArrayDataProvider;
use yii\db\Expression;
use yii\rbac\Item;
use yii\validators\DateValidator;

/**
 * AuthItemSearch represents the model behind the search form about `backend\models\AuthItem`.
 */
class AuthItemSearch extends AuthItem
{

    public function __construct($config = [])
    {
        parent::__construct($config);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description', 'rule_name', 'data', 'created_at', 'updated_at'], 'safe'],
            [['type'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = AuthItem::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }


//        var_dump(mktime(null,null,null, date_create_from_format('d/m/Y', '29/07/2015')->format('m'), date_create_from_format('d/m/Y', '29/07/2015')->format('d'), date_create_from_format('d/m/Y', '29/07/2015')->format('y')));
//        var_dump(date_create_from_format('d/m/Y','1438102800', new \DateTimeZone(Yii::$app->getTimeZone()))->format('m'));

//        var_dump(time());
//        var_dump(date('d/m/Y', '29/07/2015'));
//        var_dump(self::verifyDate('29/07/2015'));
//        return;

        $query->andFilterWhere([
            'type' => $this->type,
            'created_at' => date_create_from_format('d/m/Y', $this->created_at) ?
                mktime(null,null,null, date_create_from_format('d/m/Y', $this->created_at)->format('m'), date_create_from_format('d/m/Y', $this->created_at)->format('d'), date_create_from_format('d/m/Y', $this->created_at)->format('y')) : $this->created_at,
            'updated_at' => date_create_from_format('d/m/Y', $this->updated_at) ?
                mktime(null,null,null, date_create_from_format('d/m/Y', $this->updated_at)->format('m'), date_create_from_format('d/m/Y', $this->updated_at)->format('d'), date_create_from_format('d/m/Y', $this->updated_at)->format('y')) : $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'rule_name', $this->rule_name])
            ->andFilterWhere(['like', 'data', $this->data]);

        if ($this->type != Item::TYPE_ROLE) {
            $models = $dataProvider->models;
            if ($this->type == Item::TYPE_PERMISSION) {
                $count = 0;
                foreach ($models as $model) {
                    if ($model->name[0] === '/') {
                        unset($models[$count]);
                        $count = 0;
                    }
                    $count++;
                }
            } else {
                $count=0;
                foreach($models as $model) {
                    $count++;
                    if ($model->name[0] !== '/') {
                        unset($models[$count]);
                        $count=0;
                    }
                }
            }
            $dataProvider->models = array_values($models);
        }

        return $dataProvider;
    }

    static public function verifyDate($date, $strict = true)
    {
        $dateTime = \DateTime::createFromFormat('d/m/Y', $date);
        if ($strict) {
            $errors = \DateTime::getLastErrors();
            if (!empty($errors['warning_count'])) {
                return false;
            }
        }
        return $dateTime !== false;
    }
}
