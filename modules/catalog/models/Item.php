<?php

namespace reketaka\helps\modules\catalog\models;

use common\helpers\BaseHelper;
use reketaka\helps\modules\catalog\Module;
use yii\helpers\ArrayHelper;

class Item extends BaseModel {

    CONST EVENT_CHANGE_PRICE = 'eventChangePrice';
    CONST EVENT_CHANGE_AMOUNT = 'eventChangeAmount';

    public $behaviorTimestamp = true;

    public static function tableName()
    {
        return Module::$tablePrefix."catalog_item";
    }

    public function rules(){
        return [
            [['total_amount'], 'integer'],
            [['total_amount'], 'default', 'value'=>0],
            [['title', 'uid'], 'string'],
            [['uid'], 'unique']
        ];
    }

    public function attributeLabels()
    {
        return [
            'title'=>\Yii::t('app', 'title'),
            'uid'=>\Yii::t('app','uid'),
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPrices(){
        return $this->hasMany(ItemPrice::class, ['item_id'=>'id']);
    }

    /**
     * Возвращает количество товара на определенном складе
     * @param $id
     * @return int
     */
    public function getAmountStoreById($id){
        $itemStores = ArrayHelper::index($this->itemsStores, 'id');

        return isset($itemStores[$id])?$itemStores[$id]->amount:0;
    }

    /**
     * Изменяет количество товара на определенных складах ['uid_store'=>34, 'uid_store2'=>54]
     * @param $storeData
     * @throws \yii\base\Exception
     */
    public function setAmountStore($storeData){

        foreach($storeData as $storeUid=>$amount){
            if(!$store = Store::findOne(['uid'=>$storeData])){
                continue;
            }

            $itemStore = ItemStore::getOrCreate([
                'item_id'=>$this->id,
                'store_id'=>$store->id
            ]);

            $itemStore->amount = $amount;
            $itemStore->save();
        }

        $allAmount = array_sum($storeData);

        $this->total_amount = $allAmount;
        $this->save();

        return $this;
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