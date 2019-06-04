<?php

namespace reketaka\helps\common\behaviors;

use common\helpers\BaseHelper;
use yii\base\Behavior;
use yii\caching\TagDependency;
use yii\db\ActiveRecord;
use Yii;

class CacheTagBehavior extends Behavior{

    public $tagNames = [];

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'deleteCache',
            ActiveRecord::EVENT_AFTER_UPDATE => 'deleteCache',
            ActiveRecord::EVENT_AFTER_DELETE => 'deleteCache',
        ];
    }

    public function deleteCache()
    {
        foreach($this->tagNames  as $tagName=>$tagParams){

            $placeholders = [];
            foreach ((array) $tagParams as $fieldName) {
                $placeholders['{' . $fieldName . '}'] = $this->owner->{$fieldName};
            }

            $tag = strtr($tagName, $placeholders);

//            \Yii::info("Удаляем тег кеша $tag", __METHOD__);

            TagDependency::invalidate(Yii::$app->cache, $tag);
        }
    }

}