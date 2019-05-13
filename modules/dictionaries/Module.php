<?php

namespace reketaka\helps\modules\dictionaries;

class Module extends \yii\base\Module{


    public function init(){
        parent::init();

        if (\Yii::$app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'reketaka\helps\modules\dictionaries\commands';
        }
    }

}