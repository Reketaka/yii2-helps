<?php

namespace reketaka\helps\modules\dictionaries\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use reketaka\helps\modules\dictionaries\models\DictionariesName;

/**
 * DictionariesNameSearch represents the model behind the search form of `reketaka\helps\modules\dictionaries\models\DictionariesName`.
 */
class DictionariesNameSearch extends DictionariesName
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['title', 'alias', 'description'], 'safe'],
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
        $query = DictionariesName::find();

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
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'alias', $this->alias])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
