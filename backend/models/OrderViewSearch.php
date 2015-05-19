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
            [['id', 'order_date', 'receiving_date', 'tax_amount'], 'integer'],
            [['customer_name', 'customer_phone_number', 'description', 'order_status'], 'safe'],
            [['shipping_fee', 'net_amount'], 'number'],
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
            'id' => $this->id,
            'order_date' => $this->order_date,
            'receiving_date' => $this->receiving_date,
            'shipping_fee' => $this->shipping_fee,
            'tax_amount' => $this->tax_amount,
            'net_amount' => $this->net_amount,
        ]);

        $query->andFilterWhere(['like', 'customer_name', $this->customer_name])
            ->andFilterWhere(['like', 'customer_phone_number', $this->customer_phone_number])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'order_status', $this->order_status]);

        return $dataProvider;
    }
}
