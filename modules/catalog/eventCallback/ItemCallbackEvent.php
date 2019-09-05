<?php

namespace reketaka\helps\modules\catalog\eventCallback;

use common\models\CommonModel;
use reketaka\helps\modules\catalog\events\AmountChangeEvent;
use reketaka\helps\modules\catalog\events\PriceChangeEvent;
use reketaka\helps\modules\catalog\models\Item;
use yii\base\Event;

class ItemCallbackEvent extends CommonModel{

    public static function changeAttribute(Event $event){
        /**
         * @var $sender Item
         */
        $sender = $event->sender;

        if(($oldPrice = $sender->getOldAttribute('price')) != ($newPrice = $sender->getAttribute('price'))){

            $event = new PriceChangeEvent([
                'newPrice' => $newPrice,
                'oldPrice' => $oldPrice
            ]);

            $sender->trigger(Item::EVENT_CHANGE_PRICE, $event);

        }

        if(($oldAmount = $sender->getOldAttribute('total_amount')) != ($newAmount = $sender->getAttribute('total_amount'))){

            $event = new AmountChangeEvent([
                'newAmount' => $newAmount,
                'oldAmount' => $oldAmount
            ]);

            $sender->trigger(Item::EVENT_CHANGE_AMOUNT, $event);
        }

    }

}