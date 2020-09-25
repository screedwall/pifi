<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Courses;

/**
 * CoursesSearch represents the model behind the search form of `app\models\Courses`.
 */
class CoursesSearch extends Courses
{
    public $teacher;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'price'], 'integer'],
            [['name', 'shortDescription', 'description', 'dateFrom', 'dateTo', 'subject', 'examType', 'teacher'], 'safe'],
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
        $query = Courses::find();
        $query->joinWith(['teacher']);
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

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
            'price' => $this->price,
        ]);

        $query->andFilterWhere(['like', 'courses.name', $this->name])
            ->andFilterWhere(['like', 'shortDescription', $this->shortDescription])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'courses.subject', $this->subject])
            ->andFilterWhere(['like', 'examType', $this->examType])
            ->andFilterWhere(['like', 'teachers.name', $this->teacher]);

        return $dataProvider;
    }
}
