<?php

namespace app\models;

use app\controllers\AppController;
use app\models\Users;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * UsersSearch represents the model behind the search form of `app\models\Users`.
 */
class TinkoffPaySearch extends TinkoffPay
{
    public $month;
    public $user;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'courseId', 'monthId', 'userId'], 'integer'],
            [['type', 'status'], 'string'],
            [['amount'], 'double'],
            [['response', 'createdAt', 'user', 'month'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = TinkoffPay::find()
            ->joinWith('course')
            ->joinWith('month')
            ->joinWith('user');
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeParam' => false,
                'pageSize' => 15
            ]
        ]);

        $dataProvider->sort->attributes['user'] = [
            'asc' => ['users.name' => SORT_ASC],
            'desc' => ['users.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['month'] = [
            'asc' => ["concat_ws(' ', \"courses\".\"name\", \"months\".\"name\")" => SORT_ASC],
            'desc' => ["concat_ws(' ', \"courses\".\"name\", \"months\".\"name\")" => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id
        ]);

        $query->andFilterWhere(['like', 'UPPER(users.name)', mb_strtoupper($this->user)])
            ->andFilterWhere(['like', "UPPER(concat_ws(' ', \"courses\".\"name\", \"months\".\"name\"))", mb_strtoupper($this->month)])
            ->andFilterWhere(['like', 'cast(amount as varchar)', $this->amount])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'status', $this->status]);

        if(!empty($this->createdAt))
            $query->andFilterWhere(['between', 'tinkoffpay.createdAt', date_create_from_format('d.m.Y', $this->createdAt)->format('Y-m-d 00:00:00'), date_create_from_format('d.m.Y', $this->createdAt)->format('Y-m-d 23:59:59')]);

        return $dataProvider;
    }
}
