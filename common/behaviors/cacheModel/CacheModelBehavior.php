<?php

namespace reketaka\helps\common\behaviors\cacheModel;

use reketaka\helps\common\behaviors\cacheModel\BaseCacheModel;
use yii\base\Behavior;

class CacheModelBehavior extends Behavior{

    private $_cacheModel;
    public $cacheModelClass = null;

    /**
     * Возвращает модель которая работает с кешированными данными заявки
     * @return BaseCacheModel
     */
    public function getCache(){
        if($this->_cacheModel){
            return $this->_cacheModel;
        }

        $this->_cacheModel = new $this->cacheModelClass([
            'owner' => $this->owner
        ]);

        return $this->_cacheModel;
    }

}