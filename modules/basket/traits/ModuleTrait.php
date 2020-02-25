<?php

namespace reketaka\helps\modules\basket\traits;


use reketaka\helps\modules\basket\Module;

trait ModuleTrait
{
    /**
     * @return Module
     */
    public function getModule()
    {
        return \Yii::$app->getModule('basket');
    }

}