<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Offer;

/**
 * OfferSearch represents the model behind the search form about `common\models\Offer`.
 */
class OfferSearch extends Offer
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'active'], 'integer'],
            [['price'], 'number'],
            [['description', 'product_id', 'start_date', 'end_date'], 'safe'],
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
        $query = Offer::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith('product');

        $query->andFilterWhere([
            'id' => $this->id,
//            'product_id' => $this->product_id,
            'price' => $this->price,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'offer.active' => $this->active,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'product.name', $this->product_id]);

        return $dataProvider;
    }
}
