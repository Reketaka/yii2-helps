<?php

namespace reketaka\helps\modules\moneyCourse\commands;

use reketaka\helps\modules\moneyCourse\models\MoneyCourse;
use yii\console\Controller;
use yii\helpers\Console;
use yii\httpclient\Client;

class InitController extends Controller{

    /**
     * Запускает обновление курсов валют со сбербанка
     */
    public function actionRun(){
        MoneyCourse::refreshValutes();
    }

}