<?php

namespace reketaka\helps\modules\basket\commands;

use reketaka\helps\modules\basket\models\BasketClear;
use yii\console\Controller;

class InitController extends Controller{

    public function actionClean(){

        BasketClear::clearOld();
        BasketClear::clearRemoveBasketItem();

    }

}