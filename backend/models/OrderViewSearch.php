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
            [['order_id', 'quantity', 'order_status_id'], 'integer'],
            [['customer_name', 'customer_phone_no', 'product_name'], 'safe'],
            [['sell_price', 'discount'], 'number'],
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
            'quantity' => $this->quantity,
            'sell_price' => $this->sell_price,
            'discount' => $this->discount,
            'order_status_id' => $this->order_status_id,
        ]);

        $query->andFilterWhere(['like', 'customer_name', $this->customer_name])
            ->andFilterWhere(['like', 'customer_phone_no', $this->customer_phone_no])
            ->andFilterWhere(['like', 'product_name', $this->product_name]);

        return $dataProvider;
    }
}
