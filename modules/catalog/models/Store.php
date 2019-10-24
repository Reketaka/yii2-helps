<?php

namespace reketaka\helps\modules\catalog\models;


use common\helpers\BaseHelper;
use reketaka\helps\common\models\CommonRecord;
use reketaka\helps\modules\catalog\Module;

/**
 * Class Store
 * @package reketaka\helps\modules\catalog\models
 *
 * @property $title
 * @property $uid
 * @property $comment
 */
class Store extends CommonRecord{

    public $behaviorTimestamp = true;

    public static function tableName()
    {
        return Module::$tablePrefix.'store';
    }

    public function rules(){
        return [
            [['title', 'uid', 'comment'], 'string'],
            [['uid', 'comment'], 'default', 'value'=>null],
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

        BaseHelper::deleteAll(ItemStore::class, ['store_id'=>$this->id]);

        return true;
    }

}