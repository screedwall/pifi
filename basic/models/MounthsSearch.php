<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models;

/**
 * MounthsSearch represents the model behind the search form of `app\models\mounths`.
 */
class MounthsSearch extends mounths
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'course'], 'integer'],
            [['name', 'dateFrom', 'dateTo'], 'safe'],
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
        $query = mounths::find();

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
            'dateFrom' => $this->dateFrom,
            'dateTo' => $this->dateTo,
            'course' => $this->course,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
    public function getCourse()
    {
        return $this->hasOne(Courses::class, ['id' => 'course']);
    }
}
