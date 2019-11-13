<?php

namespace reketaka\helps\modules\basket\models;


use yii\base\Model;
use yii\db\Expression;

class BasketClear extends Model{

    /**
     * Удаляет корзины которые не использовались месяц
     * @throws \Exception
     */
    public static function clearOld(){

        $dayToDelete = \Yii::$app->getModule('basket')->deleteBasketDay;

        $date = (new \DateTime())->modify("-$dayToDelete day")->format("Y-m-d");


        Basket::deleteAll(['<=', new Expression("DATE(updated_at)"), $date]);
    }

    /**
     * Удаляет товары корзины которые уже исчезли из системы как товары
     */
    public static function clearRemoveBasketItem(){

        $basket = \Yii::$app->getModule('basket');
        if(!$productClass = $basket->productClass){
            return false;
        }

        $basketItems = BasketItem::find()
            ->from(['bi'=>BasketItem::tableName()])
            ->leftJoin(['ci'=>$productClass::tableName()], "ci.id = bi.item_id")
            ->where(['IS', 'ci.id', new Expression("NULL")])
            ->all();

        foreach($basketItems as $basketItem){
            $basketItem->delete();
        }

        return true;
    }

}