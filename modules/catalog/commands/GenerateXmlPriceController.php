<?php

namespace reketaka\helps\modules\catalog\commands;

use reketaka\helps\modules\catalog\models\GenerateXmlPriceWork;
use yii\console\Controller;

class GenerateXmlPriceController extends Controller{

    public function actionGenerate(){

        (new GenerateXmlPriceWork())->run();

    }


}