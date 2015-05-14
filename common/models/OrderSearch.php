<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Order;

/**
 * OrderSearch represents the model behind the search form about `common\models\Order`.
 */
class OrderSearch extends Order
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'voucher_id', 'address_id', 'order_status_id'], 'integer'],
            [['order_date', 'receiving_date', 'description'], 'safe'],
            [['shipping_fee', 'discount', 'tax_amount', 'net_amount'], 'number'],
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
        $query = Order::find();

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
            'discount' => $this->discount,
            'tax_amount' => $this->tax_amount,
            'net_amount' => $this->net_amount,
            'user_id' => $this->user_id,
            'voucher_id' => $this->voucher_id,
            'address_id' => $this->address_id,
            'order_status_id' => $this->order_status_id,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
