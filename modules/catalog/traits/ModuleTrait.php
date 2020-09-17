<?php

namespace reketaka\helps\modules\catalog\traits;

use reketaka\helps\modules\catalog\Module;

/**
 * Trait ModuleTrait
 * @package reketaka\helps\modules\catalog\traits
 *
 * @property Module $module
 */
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