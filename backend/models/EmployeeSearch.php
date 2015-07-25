<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Employee;

/**
 * EmployeeSearch represents the model behind the search form about `backend\models\Employee`.
 */
class EmployeeSearch extends Employee
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'dob', 'phone_number', 'start_date', 'status', 'address_id'], 'integer'],
            [['full_name', 'password', 'gender', 'email', 'image', 'auth_key', 'password_reset_token'], 'safe'],
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
        $query = Employee::find();

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
                mktime(null,null,null, date_create_from_format('d/m/Y', $this->dob)->format('m'), date_create_from_format('d/m/Y', $this->dob)->format('d'), date_create_from_format('d/m/Y', $this->dob)->format('y')): $this->dob,
            'phone_number' => $this->phone_number,
            'start_date' => date_create_from_format('d/m/Y', $this->start_date) ?
                mktime(null,null,null, date_create_from_format('d/m/Y', $this->start_date)->format('m'), date_create_from_format('d/m/Y', $this->start_date)->format('d'), date_create_from_format('d/m/Y', $this->start_date)->format('y')) : $this->start_date,
            'status' => $this->status,
            'address_id' => $this->address_id,
        ]);

        $query->andFilterWhere(['like', 'full_name', $this->full_name])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token]);

        return $dataProvider;
    }
}
