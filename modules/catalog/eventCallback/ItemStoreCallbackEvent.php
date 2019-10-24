<?php

namespace reketaka\helps\modules\catalog\eventCallback;

use common\models\BaseHelper;
use reketaka\helps\modules\catalog\models\Item;
use reketaka\helps\modules\catalog\models\ItemStore;
use reketaka\helps\modules\catalog\models\PriceType;
use yii\base\Event;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\db\AfterSaveEvent;

class ItemStoreCallbackEvent extends Model{

    /**
     * Всегда оставляет в системе дефолтный тип цен
     * @param Event $event
     * @return bool
     */
    public static function addTotalAmount(Event $event){

        /**
         * @var $sender ItemStore
         */
        $sender = $event->sender;

        /**
         * @var $item Item
         */
        if(!$item = $sender->item){
            return false;
        }

        $item->total_amount += $sender->amount;
        $item->save();

        return true;
    }

    public static function changeTotalAmount(Event $event){
        /**
         * @var $sender ItemStore
         */
        $sender = $event->sender;

        /**
         * @var $item Item
         */
        if(!$item = $sender->item){
            return false;
        }

        if(($event->name == ActiveRecord::EVENT_BEFORE_UPDATE) && ($sender->getOldAttribute('amount') != $sender->amount)){

            $item->total_amount = $item->total_amount-$sender->getOldAttribute('amount')+$sender->amount;
            $item->total_amount = abs($item->total_amount);
            $item->save();
            return true;
        }

        if($event->name == ActiveRecord::EVENT_BEFORE_DELETE){
            $item->total_amount -= $sender->amount;
            $item->save();
            return true;
        }








    }

}