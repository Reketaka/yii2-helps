<?php

namespace reketaka\helps\modules\adminMenu\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use reketaka\helps\modules\adminMenu\models\MenuItemUser;

/**
 * MenuItemUserSearch represents the model behind the search form of `backend\models\menu\MenuItemUser`.
 */
class MenuItemUserSearch extends MenuItemUser
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'menu_item_id', 'menu_section_id', 'order', 'user_id'], 'integer'],
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
        $query = MenuItemUser::find()
            ->with([
                'menuItem',
                'menuSection',
                'user'
            ]);

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
            'menu_item_id' => $this->menu_item_id,
            'menu_section_id' => $this->menu_section_id,
            'order' => $this->order,
            'user_id'=>$this->user_id
        ]);

        return $dataProvider;
    }
}
