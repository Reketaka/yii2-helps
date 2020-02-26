<?php

namespace reketaka\helps\modules\catalog\models;


use reketaka\helps\modules\catalog\traits\ModuleTrait;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * MenuItemSearch represents the model behind the search form of `backend\models\menu\MenuItem`.
 */
class StoreSearch extends Store
{
    use ModuleTrait;

    public $behaviorTimestamp = false;
    /**
     * @inheritdoc
     */
    public function rules(){
        return [
            [['title', 'uid', 'comment'], 'string'],
            [['uid'], 'unique']
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
        $query = $this->getModule()->storeClass::find()
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
            'title'=>$this->title,
            'comment'=>$this->comment,
            'uid'=>$this->uid
        ]);

        return $dataProvider;
    }
}
