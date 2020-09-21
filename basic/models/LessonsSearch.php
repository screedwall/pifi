<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Lessons;

/**
 * LessonsSearch represents the model behind the search form of `app\models\Lessons`.
 */
class LessonsSearch extends Lessons
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'mounth', 'course'], 'integer'],
            [['name', 'shortDescription', 'description', 'video', 'lessonDate', 'homeworkDate'], 'safe'],
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
        $query = Lessons::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'lessonDate' => $this->lessonDate,
            'homeworkDate' => $this->homeworkDate,
            'mounth' => $this->mounth,
            'course' => $this->course,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'shortDescription', $this->shortDescription])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'video', $this->video]);

        return $dataProvider;
    }
}
