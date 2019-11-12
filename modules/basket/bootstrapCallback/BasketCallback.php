<?php

namespace reketaka\helps\modules\basket\bootstrapCallback;

use common\helpers\BaseHelper;
use reketaka\helps\common\helpers\Bh;
use reketaka\helps\modules\basket\models\Basket;
use reketaka\helps\modules\basket\models\BasketItem;
use yii\base\Event;
use yii\base\Model;

class BasketCallback extends Model{

    public static function onModifyItem(Event $event){
        /**
         * @var $basketItem BasketItem
         * @var $basket Basket
         */
        $basketItem = $event->sender;
        $basket = $basketItem->basket;


        if($event->name == 'beforeUpdate') {
            $basket->total_amount = $basket->total_amount - $basketItem->getOldAttribute('amount') + $basketItem->amount;
            $basket->total_price = $basket->total_price - ($basketItem->getOldAttribute('amount')*$basketItem->getOldAttribute('price')) + ($basketItem->price*$basketItem->amount);
        }

        if($event->name == 'beforeInsert'){
            $basket->total_amount += $basketItem->amount;
            $basket->total_price += $basketItem->price * $basketItem->amount;
        }

        if($event->name == 'beforeDelete'){
            $basket->total_amount -= $basketItem->amount;
            $basket->total_price -= $basketItem->price * $basketItem->amount;

            $basket->total_amount = $basket->total_amount <= 0?0:$basket->total_amount;
            $basket->total_price = $basket->total_price <= 0?0:$basket->total_price;
        }

        $basket->save();


        return true;
    }

    public static function onFullDeleteBasket(Event $event){
        /**
         * @var $basket Basket
         */
        $basket = $event->sender;

        BasketItem::deleteAll(['basket_id'=>$basket->id]);
    }

}