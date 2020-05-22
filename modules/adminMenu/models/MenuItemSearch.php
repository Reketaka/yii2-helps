<?php

namespace reketaka\helps\modules\adminMenu\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use reketaka\helps\modules\adminMenu\models\MenuItem;
use const SORT_DESC;

/**
 * MenuItemSearch represents the model behind the search form of `backend\models\menu\MenuItem`.
 */
class MenuItemSearch extends MenuItem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'section_id', 'order'], 'integer'],
            [['title', 'alias', 'url'], 'safe'],
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
        $query = MenuItem::find()
            ->with([
                'section'
            ])
            ->orderBy(['id'=>SORT_DESC]);

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
            'section_id' => $this->section_id,
            'order' => $this->order,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'alias', $this->alias])
            ->andFilterWhere(['like', 'url', $this->url]);

        return $dataProvider;
    }
}
