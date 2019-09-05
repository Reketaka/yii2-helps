<?php

namespace reketaka\helps\modules\catalog\events;

use yii\base\Event;

class PriceChangeEvent extends Event{

    public $oldPrice;
    public $newPrice;

}