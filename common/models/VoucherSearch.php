<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Voucher;

/**
 * VoucherSearch represents the model behind the search form about `common\models\Voucher`.
 */
class VoucherSearch extends Voucher
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'discount', 'active', 'order_id'], 'integer'],
            [['name', 'code', 'start_date', 'end_date'], 'safe'],
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
        $query = Voucher::find();

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
            'discount' => $this->discount,
            'start_date' => date_create_from_format('d/m/Y', $this->start_date) ?
                mktime(null,null,null, date_create_from_format('d/m/Y', $this->start_date)->format('m'), date_create_from_format('d/m/Y', $this->start_date)->format('d'), date_create_from_format('d/m/Y', $this->start_date)->format('y')) :$this->start_date,
            'end_date' => date_create_from_format('d/m/Y', $this->end_date) ?
                mktime(null,null,null, date_create_from_format('d/m/Y', $this->end_date)->format('m'), date_create_from_format('d/m/Y', $this->end_date)->format('d'), date_create_from_format('d/m/Y', $this->end_date)->format('y')) : $this->end_date,
            'active' => $this->active,
            'order_id' => $this->order_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'code', $this->code]);

        return $dataProvider;
    }
}
