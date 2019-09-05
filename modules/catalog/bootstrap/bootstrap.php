<?php


use reketaka\helps\modules\catalog\models\ItemStore;
use yii\base\Event;

Event::on(ItemStore::class, ItemStore::EVENT_AFTER_UPDATE, function($event){




});



?>