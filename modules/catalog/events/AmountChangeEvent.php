<?php

namespace reketaka\helps\modules\catalog\events;

use yii\base\Event;

/**
 * Class AmountChangeEvent
 * @package reketaka\helps\modules\catalog\events
 *
 * Ивент срабатывает при изменении общего количества товара при изменении или создание нового товара
 */
class AmountChangeEvent extends Event{

    public $oldAmount;
    public $newAmount;

}