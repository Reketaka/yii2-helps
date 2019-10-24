<?php

namespace reketaka\helps\modules\catalog\models;


use reketaka\helps\common\models\CommonRecord;
use reketaka\helps\modules\catalog\Module;

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
            'title'=>\Yii::t('app', 'title'),
            'item_id'=>\Yii::t('app', 'item_id'),
            'store_id'=>\Yii::t('app', 'store_id'),
            'amount'=>\Yii::t('app','amount'),
            'created_at'=>\Yii::t('app','created_at'),
            'updated_at'=>\Yii::t('app', 'updated_at')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStore(){
        return $this->hasOne(Store::class, ['id'=>'store_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem(){
        return $this->hasOne(Item::class, ['id'=>'item_id']);
    }

}