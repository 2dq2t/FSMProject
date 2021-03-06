<?php

namespace common\models;

use backend\components\ParserDateTime;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Customer;

/**
 * CustomerSearch represents the model behind the search form about `common\models\Customer`.
 */
class CustomerSearch extends Customer
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'address_id'], 'integer'],
            [['username', 'password', 'avatar', 'dob', 'gender', 'auth_key', 'password_reset_token', 'created_at', 'updated_at'], 'safe'],
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
     * Creates file provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Customer::find();

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
            'dob' => ParserDateTime::parseToTimestamp($this->dob) ?
                ParserDateTime::parseToTimestamp($this->dob) : $this->dob,
            'created_at' => ParserDateTime::parseToTimestamp($this->created_at) ?
                ParserDateTime::parseToTimestamp($this->created_at) : $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
            'address_id' => $this->address_id,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'avatar', $this->avatar])
            ->andFilterWhere(['=', 'gender', $this->gender])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token]);

        return $dataProvider;
    }
}
