<?php

namespace reketaka\helps\modules\seo;

class Module extends \yii\base\Module{

    public function init(){
        parent::init();

        if (\Yii::$app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'reketaka\helps\modules\seo\commands';
        }
    }

}