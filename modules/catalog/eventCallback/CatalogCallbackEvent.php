<?php

namespace reketaka\helps\modules\catalog\eventCallback;

use reketaka\helps\modules\catalog\models\Catalog;
use reketaka\helps\modules\catalog\models\Item;
use reketaka\helps\modules\catalog\models\PriceType;
use yii\base\Event;
use yii\base\Model;
use yii\db\Expression;

class CatalogCallbackEvent extends Model{

    /**
     * При удалении каталога проставляет всем товарам этого каталога catalog_id = NULL
     * @param Event $event
     * @return bool
     */
    public static function onDelete(Event $event){

        /**
         * @var $catalog Catalog
         */
        $catalog = $event->sender;

        Item::updateAll(['catalog_id'=>new Expression("NULL")], ['catalog_id'=>$catalog->id]);

        return true;
    }

}