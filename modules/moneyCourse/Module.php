<?php

namespace reketaka\helps\modules\moneyCourse;

class Module extends \yii\base\Module{

    public $refreshSberUrl = 'http://www.cbr.ru/scripts/XML_daily.asp';

    public function init(){
        parent::init();

        if (\Yii::$app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'reketaka\helps\modules\moneyCourse\commands';
        }
    }

}