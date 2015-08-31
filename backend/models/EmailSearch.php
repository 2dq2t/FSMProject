<?php

namespace backend\models;

use backend\components\ParserDateTime;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Email;

/**
 * EmailSearch represents the model behind the search form about `backend\models\Email`.
 */
class EmailSearch extends Email
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['subject', 'message', 'employee_id','create_at'], 'safe'],
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
        $query = Email::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith('employee');

        $query->andFilterWhere([
            'id' => $this->id,
            'create_at' => ParserDateTime::parseToTimestamp($this->create_at) ? ParserDateTime::parseToTimestamp($this->create_at) : $this->create_at,
//            'employee_id' => $this->employee_id,
        ]);

        $query->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'message', $this->message])
            ->andFilterWhere(['like', 'employee.full_name', $this->employee_id]);

        return $dataProvider;
    }
}
