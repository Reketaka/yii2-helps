<?php

namespace reketaka\helps\modules\basket\bootstrap;

use reketaka\helps\modules\basket\models\Basket;
use reketaka\helps\modules\basket\models\BasketItem;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\Event;

class Bootstrap implements BootstrapInterface{


    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {

        Event::on(BasketItem::class, BasketItem::EVENT_BEFORE_UPDATE, ['reketaka\helps\modules\basket\bootstrapCallback\BasketCallback', 'onModifyItem']);
        Event::on(BasketItem::class, BasketItem::EVENT_BEFORE_INSERT, ['reketaka\helps\modules\basket\bootstrapCallback\BasketCallback', 'onModifyItem']);
        Event::on(BasketItem::class, BasketItem::EVENT_BEFORE_DELETE, ['reketaka\helps\modules\basket\bootstrapCallback\BasketCallback', 'onModifyItem']);

        Event::on(Basket::class, BasketItem::EVENT_BEFORE_DELETE, ['reketaka\helps\modules\basket\bootstrapCallback\BasketCallback', 'onFullDeleteBasket']);





    }
}