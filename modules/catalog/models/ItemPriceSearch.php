<?php

namespace reketaka\helps\modules\catalog\models;


use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * MenuItemSearch represents the model behind the search form of `backend\models\menu\MenuItem`.
 */
class ItemPriceSearch extends ItemPrice
{
    public $behaviorTimestamp = false;

    /**
     * @inheritdoc
     */
    public function rules(){
        return [
            [['item_id', 'price_type_id'], 'integer'],
            [['price'], 'string']
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
        $query = ItemPrice::find()
            ->with([
                'item',
                'priceType'
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
            'price_type_id'=>$this->price_type_id,
            'price'=>$this->price
        ]);

        return $dataProvider;
    }
}
