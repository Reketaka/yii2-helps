<?php

namespace reketaka\helps\modules\catalog\commands;

use reketaka\helps\common\helpers\Bh;
use reketaka\helps\modules\catalog\models\Catalog;
use reketaka\helps\modules\catalog\models\SendPriceWork;
use reketaka\helps\modules\catalog\Module;
use Yii;
use yii\console\Controller;

class SendPriceController extends Controller{

    public function actionSend(){

        (new SendPriceWork())->run();
        
    }

}