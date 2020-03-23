<?php

namespace reketaka\helps\modules\adminMenu\traits;

use reketaka\helps\modules\adminMenu\Module;
use Yii;

/**
 * Trait ModuleTrait
 * @package reketaka\helps\modules\adminMenu\traits
 *
 * @property Module $module
 */
trait ModuleTrait{

    /**
     * @return Module|null
     */
    public function getModule(){
        return Yii::$app->getModule('adminmenu');
    }
    
}