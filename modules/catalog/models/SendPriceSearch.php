<?php

namespace reketaka\helps\modules\catalog\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use reketaka\helps\modules\catalog\models\SendPrice;

/**
 * SendPriceSearch represents the model behind the search form of `reketaka\helps\modules\catalog\models\SendPrice`.
 */
class SendPriceSearch extends SendPrice
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'round_price', 'with_active', 'active'], 'integer'],
            [['emails'], 'safe'],
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
        $query = SendPrice::find()->orderBy(['id'=>SORT_DESC]);

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
            'round_price' => $this->round_price,
            'with_active' => $this->with_active,
            'active' => $this->active,
        ]);

        $query->andFilterWhere(['like', 'emails', $this->emails]);

        return $dataProvider;
    }
}
