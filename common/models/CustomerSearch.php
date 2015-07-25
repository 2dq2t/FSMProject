<?php

namespace common\models;

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
            'dob' => date_create_from_format('d/m/Y', $this->dob) ?
                mktime(null,null,null, date_create_from_format('d/m/Y', $this->dob)->format('m'), date_create_from_format('d/m/Y', $this->dob)->format('d'), date_create_from_format('d/m/Y', $this->dob)->format('y')) : $this->dob,
            'created_at' => date_create_from_format('d/m/Y', $this->created_at) ?
                mktime(null,null,null, date_create_from_format('d/m/Y', $this->created_at)->format('m'), date_create_from_format('d/m/Y', $this->created_at)->format('d'), date_create_from_format('d/m/Y', $this->created_at)->format('y')) : $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
            'address_id' => $this->address_id,
        ]);

//        return var_dump(date('m/d/Y H:i:s',$this->dob));

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'avatar', $this->avatar])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token]);

        return $dataProvider;
    }
}
