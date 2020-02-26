<?php

namespace reketaka\helps\modules\catalog\traits;

use reketaka\helps\modules\catalog\Module;

trait ModuleTrait
{
    /**
     * @return Module
     */
    public function getModule()
    {
        return \Yii::$app->getModule('catalog');
    }

}