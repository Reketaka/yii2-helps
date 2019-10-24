<?php

namespace reketaka\helps\modules\catalog\eventCallback;

use reketaka\helps\modules\catalog\models\PriceType;
use yii\base\Event;
use yii\base\Model;

class PriceTypeCallbackEvent extends Model{

    /**
     * Всегда оставляет в системе дефолтный тип цен
     * @param Event $event
     * @return bool
     */
    public static function setDefault(Event $event){

        /**
         * @var $sender PriceType
         */
        $sender = $event->sender;

        if($sender->default){

            PriceType::updateAll(['default'=>0], "id != {$sender->id}");
            return true;
        }

        if(PriceType::findOne(['default'=>1])){
            return true;
        }

        if(!$priceType = PriceType::find()->one()){
            return true;
        }

        PriceType::updateAll(['default'=>1], "id = {$priceType->id}");
        return true;
    }

}