<?php

namespace app\models;

use app\models\Users;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * UsersSearch represents the model behind the search form of `app\models\Users`.
 */
class MonthUsersSearch extends Users
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'email', 'vk', 'description'], 'safe'],
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
            ->joinWith(['months' => function($q) use ($params){
                return $q->where(['months.id' => $params['id']]);
            }])
            ->with(['streams' => function($query) use ($params){
                return $query
                    ->with('month')
                    ->where(['users_stream.courseId' => $params['courseId']]);
            }])
            ->with(['boughtCourses' => function($q) use ($params){
                return $q->where(['bought_courses.courseId' => $params['courseId']])
                    ->with('month');
            }]);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'forcePageParam' => false,
                'pageSizeParam' => false,
                'pageSize' => 10
            ]
        ]);

        $dataProvider->sort->attributes['vk'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['users.vk' => SORT_ASC],
            'desc' => ['users.vk' => SORT_DESC],
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
            ->andFilterWhere(['like', 'UPPER(users.vk)', mb_strtoupper($this->vk)]);

        return $dataProvider;
    }
}
