<?php

namespace backend\models;

use backend\components\ParserDateTime;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\rbac\Item;

/**
 * AuthItemSearch represents the model behind the search form about `backend\models\AuthItem`.
 */
class AuthItemSearch extends AuthItem
{
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

        $query->andFilterWhere([
            'type' => $this->type,
            'created_at' => ParserDateTime::parseToTimestamp($this->created_at) ? ParserDateTime::parseToTimestamp($this->created_at) : $this->created_at,
//            'updated_at' => ParserDateTime::parseToTimestamp($this->updated_at) ? ParserDateTime::parseToTimestamp($this->updated_at) : $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'rule_name', $this->rule_name])
            ->andFilterWhere(['like', 'data', $this->data]);

//        if ($this->type != Item::TYPE_ROLE) {
//            $models = $dataProvider->models;
//            if ($this->type == Item::TYPE_PERMISSION) {
//                $count = 0;
//                foreach ($models as $model) {
//                    if ($model->name[0] === '/') {
//                        unset($models[$count]);
//                        $count = 0;
//                    }
//                    $count++;
//                }
//            } else {
//                $count=0;
//                foreach($models as $model) {
//                    $count++;
//                    if ($model->name[0] !== '/') {
//                        unset($models[$count]);
//                        $count=0;
//                    }
//                }
//            }
//            $dataProvider->models = array_values($models);
//        }

        return $dataProvider;
    }
}
