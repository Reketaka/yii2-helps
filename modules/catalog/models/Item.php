<?php

namespace reketaka\helps\modules\catalog\models;

use common\helpers\BaseHelper;
use reketaka\helps\common\models\CommonRecord;

class Item extends CommonRecord{

    CONST EVENT_CHANGE_PRICE = 'eventChangePrice';
    CONST EVENT_CHANGE_AMOUNT = 'eventChangeAmount';

    public $behaviorTimestamp = true;

    public static function tableName()
    {
        return "reketaka_catalog_item";
    }

    public function rules(){
        return [
            [['total_amount'], 'integer'],
            [['total_amount'], 'default', 'value'=>0],
            [['title', 'uid'], 'string'],
            [['price'], 'double'],
            [['uid'], 'unique']
        ];
    }

    public function attributeLabels()
    {
        return [
            'title'=>\Yii::t('app', 'title'),
            'uid'=>\Yii::t('app','uid'),
            'price'=>\Yii::t('app', 'price'),
            'total_amount'=>\Yii::t('app', 'total_amount'),
            'created_at'=>\Yii::t('app','created_at'),
            'updated_at'=>\Yii::t('app', 'updated_at')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemStores(){
        return $this->hasMany(ItemStore::class, ['item_id'=>'id']);
    }

    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        BaseHelper::deleteAll(ItemStore::class, ['item_id'=>$this->id]);

        return true;
    }

}