<?php

namespace reketaka\helps\modules\catalog\models;


use reketaka\helps\common\models\CommonRecord;
use reketaka\helps\modules\catalog\Module;
use reketaka\helps\modules\catalog\traits\ModuleTrait;

/**
 * Class ItemStore
 * @package reketaka\helps\modules\catalog\models
 *
 * @property $id
 * @property $item_id
 * @property $store_id
 * @property $amount
 * @property $created_at
 * @property $updated_at
 *
 * @property $item Item
 * @property $store Store
 */
class ItemStore extends CommonRecord{

    use ModuleTrait;

    public $behaviorTimestamp = true;

    public static function tableName()
    {
        return Module::$tablePrefix.'item_store';
    }

    /**
     * @return array
     */
    public function rules(){
        return [
            [['item_id', 'store_id'], 'required'],
            [['item_id'], 'exist', 'targetRelation'=>'item', 'skipOnEmpty'=>false, 'skipOnError'=>false],
            [['store_id'], 'exist', 'targetRelation'=>'store', 'skipOnEmpty'=>false, 'skipOnError'=>false],
            [['item_id', 'store_id', 'amount'], 'integer'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'title'=>Module::t('app', 'title'),
            'item_id'=>Module::t('app', 'item_id'),
            'store_id'=>Module::t('app', 'store_id'),
            'amount'=>Module::t('app','amount'),
            'created_at'=>Module::t('app','created_at'),
            'updated_at'=>Module::t('app', 'updated_at')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStore(){
        return $this->hasOne($this->getModule()->storeClass, ['id'=>'store_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem(){
        return $this->hasOne(Item::class, ['id'=>'item_id']);
    }

}