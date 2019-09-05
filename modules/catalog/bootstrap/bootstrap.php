<?php


use reketaka\helps\modules\catalog\models\Item;
use reketaka\helps\modules\catalog\models\ItemStore;
use yii\base\Event;

Event::on(Item::class, Item::EVENT_AFTER_UPDATE, ['reketaka\helps\modules\catalog\eventCallback\ItemCallbackEvent', 'changeAttribute']);
Event::on(Item::class, Item::EVENT_AFTER_INSERT, ['reketaka\helps\modules\catalog\eventCallback\ItemCallbackEvent', 'changeAttribute']);



//Event::on(Item::class, Item::EVENT_CHANGE_PRICE, []);
//Event::on(Item::class, Item::EVENT_CHANGE_AMOUNT, []):



?>