<?php

namespace reketaka\helps\modules\catalog\models;


use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * MenuItemSearch represents the model behind the search form of `backend\models\menu\MenuItem`.
 */
class ItemStoreSearch extends ItemStore
{
    public $behaviorTimestamp = false;
    /**
     * @inheritdoc
     */
    public function rules(){
        return [
            [['item_id', 'store_id', 'amount'], 'integer'],
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
        $query = ItemStore::find()
            ->with([
                'item',
                'store'
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
            'id'=>$this->id,
            'item_id'=>$this->item_id,
            'store_id'=>$this->store_id,
            'amount'=>$this->amount
        ]);

        return $dataProvider;
    }
}
