<?php

namespace reketaka\helps\common\behaviors\cacheModel;

use Yii;
use yii\base\BaseObject;
use yii\caching\Cache;

abstract class BaseCacheModel extends BaseObject{

    private $_owner;

    protected $cacheName = 'cache';
    /**
     * @var Cache $cache
     */
    protected $cache;

    public function init()
    {
        $this->cache = Yii::$app->{$this->cacheName};
    }

    abstract public function getCacheKeys();

    public function setOwner(&$model){
        $this->owner = $model;
        return $this;
    }

    public function getOwner(){
        return $this->_owner;
    }

    public function formatKey($keyName){
        return $this->owner->id."_".$keyName;
    }

    public function cleanAll(){
        foreach($this->getCacheKeys() as $cacheKey){
            $this->cache->delete($this->formatKey($cacheKey));
        }
    }



}