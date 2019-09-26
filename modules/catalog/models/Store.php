<?php

namespace reketaka\helps\modules\catalog\models;


use common\helpers\BaseHelper;
use reketaka\helps\common\models\CommonRecord;
use reketaka\helps\modules\catalog\Module;

class Store extends CommonRecord{

    public $behaviorTimestamp = true;

    public static function tableName()
    {
        return Module::$tablePrefix.'store';
    }

    public function rules(){
        return [
            [['title', 'uid', 'comment'], 'string'],
            [['uid'], 'unique']
        ];
    }

    public function attributeLabels()
    {
        return [
            'title'=>\Yii::t('app', 'title'),
            'uid'=>\Yii::t('app','uid'),
            'comment'=>\Yii::t('app', 'comment'),
            'created_at'=>\Yii::t('app','created_at'),
            'updated_at'=>\Yii::t('app', 'updated_at')
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