<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Product;

/**
 * ProductSearch represents the model behind the search form about `common\models\Product`.
 */
class ProductSearch extends Product
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'barcode', 'quantity_in_stock', 'sold', 'tax', 'create_date', 'active', 'category_id', 'unit_id'], 'integer'],
            [['name', 'description', 'intro'], 'safe'],
            [['price'], 'number'],
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
        $query = Product::find();

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
            'barcode' => $this->barcode,
            'price' => $this->price,
            'quantity_in_stock' => $this->quantity_in_stock,
            'sold' => $this->sold,
            'tax' => $this->tax,
            'create_date' => $this->create_date,
            'active' => $this->active,
            'category_id' => $this->category_id,
            'unit_id' => $this->unit_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'intro', $this->intro]);

        return $dataProvider;
    }
}
