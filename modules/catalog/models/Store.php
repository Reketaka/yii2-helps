<?php

namespace reketaka\helps\modules\catalog\models;


use reketaka\helps\common\helpers\Bh;
use reketaka\helps\common\models\CommonRecord;
use reketaka\helps\modules\catalog\Module;

/**
 * Class Store
 * @package reketaka\helps\modules\catalog\models
 *
 * @property $title
 * @property $uid
 * @property $alias
 * @property $comment
 */
class Store extends BaseModel {

    public $behaviorTimestamp = true;
    public $behaviorAlias = true;

    public static function tableName()
    {
        return Module::$tablePrefix.'store';
    }

    public function rules(){
        return [
            [['title', 'uid', 'comment', 'alias'], 'string'],
            [['uid', 'comment', 'alias'], 'default', 'value'=>null],
            [['uid'], 'unique']
        ];
    }

    public function attributeLabels()
    {
        return [
            'title'=>Module::t('app', 'title'),
            'uid'=>Module::t('app','uid'),
            'comment'=>Module::t('app', 'comment'),
            'created_at'=>Module::t('app','created at'),
            'updated_at'=>Module::t('app', 'updated at')
        ];
    }

    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        Bh::deleteAll(ItemStore::class, ['store_id'=>$this->id]);

        return true;
    }

}