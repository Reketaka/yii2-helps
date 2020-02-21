<?php

namespace reketaka\helps\modules\catalog\models;

use common\models\BaseHelper;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Expression;

class ItemQuery extends ActiveQuery{

    public function withPrice(){
        $query = $this->select([
            '`catalog_item`.*',
            new Expression("item_price.price as '_price'")
        ])
            ->innerJoinWith(['prices.priceType'], false)
            ->andWhere(['price_type.default'=>'1'])
            ->andWhere(['catalog_item.active'=>1])
            ->orderBy(['item_price.price'=>SORT_ASC]);


//        if(Yii::$app->hasProperty('user') && !Yii::$app->user->isGuest && ($user = Yii::$app->user->identity) && ($discount = $user->discount)){
//            $this->makeDiscount($discount->value);
//        }

        return $query;
    }

    /**
     * Выборка товаров с ценой больше нуля
     */
    public function pricePositive(){
        return $this->andWhere(['>', 'item_price.price', 0]);
    }

    public function makeDiscount($discountValue){
        $discountValue = 1-$discountValue/100;

        return $this->addSelect([
            new Expression("CEIL(item_price.price*$discountValue) as '_price'")
        ]);
    }

}