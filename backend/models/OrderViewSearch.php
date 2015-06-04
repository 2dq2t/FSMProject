<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\OrderView;

/**
 * OrderViewSearch represents the model behind the search form about `backend\models\OrderView`.
 */
class OrderViewSearch extends OrderView
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'order_status_id'], 'integer'],
            [['full_name', 'email', 'phone_number', 'address', 'order_date', 'receiving_date'], 'safe'],
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
        $query = OrderView::find();

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
            'order_id' => $this->order_id,
//            'order_date' => $this->order_date,
//            'receiving_date' => $this->receiving_date,
            'order_status_id' => $this->order_status_id,
        ]);

        $query->andFilterWhere(['like', 'full_name', $this->full_name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'order_date', strtotime($this->order_date)])
            ->andFilterWhere(['like', 'receiving_date', strtotime($this->receiving_date)])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number])
            ->andFilterWhere(['like', 'address', $this->address]);

        return $dataProvider;
    }
}
