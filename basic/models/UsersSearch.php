<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Users;

/**
 * UsersSearch represents the model behind the search form of `app\models\Users`.
 */
class UsersSearch extends Users
{
    public $teacher;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'role'], 'integer'],
            [['name', 'email', 'vk', 'description', 'authKey', 'password', 'teacher'], 'safe'],
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
        $query = Users::find()
            ->joinWith(['teacher']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['teacher'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['teachers.name' => SORT_ASC],
            'desc' => ['teachers.name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['name'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['users.name' => SORT_ASC],
            'desc' => ['users.name' => SORT_DESC],
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

        $query->andFilterWhere(['like', 'UPPER(users.name)', mb_strtoupper($this->name)])
            ->andFilterWhere(['like', 'UPPER(email)', mb_strtoupper($this->email)])
            ->andFilterWhere(['like', 'UPPER(vk)', mb_strtoupper($this->vk)])
            ->andFilterWhere(['like', 'users.description', $this->description])
            ->andFilterWhere(['=', 'users.role', $this->role])
            ->andFilterWhere(['like', 'teachers.name', $this->teacher]);

        return $dataProvider;
    }
}
