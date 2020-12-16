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
            [['id'], 'integer'],
            [['name', 'shortDescription', 'description', 'subject', 'examType', 'teacher'], 'safe'],
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
        $query = Courses::find()
            ->with('months')
            ->joinWith(['teacher'])
            ->orderBy(['id' => SORT_DESC]);

        if(\Yii::$app->user->identity->isTeacher())
            $query = $query->andWhere(['teacherId' => \Yii::$app->user->identity->teacher->id]);
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
        ]);

        $query->andFilterWhere(['like', 'UPPER(courses.name)', mb_strtoupper($this->name)])
            ->andFilterWhere(['like', 'shortDescription', $this->shortDescription])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'UPPER(courses.subject)',  mb_strtoupper($this->subject)])
            ->andFilterWhere(['like', 'examType', $this->examType])
            ->andFilterWhere(['like', 'UPPER(teachers.name)', mb_strtoupper($this->teacher)]);

        return $dataProvider;
    }
}
