<?php

namespace reketaka\helps\modules\catalog\models;

use reketaka\helps\common\helpers\Bh;
use reketaka\helps\modules\catalog\Module;
use yii\helpers\ArrayHelper;
use function get_called_class;
use function round;

/**
 * Class Item
 * @package reketaka\helps\modules\catalog\models
 *
 * @property $id
 * @property $title
 * @property $uid
 * @property $total_amount
 * @property $active
 * @property $catalog_id
 * @property $created_at
 * @property $updated_at
 *
 * @property $itemStores ItemStore[]
 * @property $prices ItemPrice[]
 * @property $catalog
 */
class Item extends BaseModel {

    CONST EVENT_CHANGE_PRICE = 'eventChangePrice';
    CONST EVENT_CHANGE_AMOUNT = 'eventChangeAmount';

    public $behaviorTimestamp = true;

    public $_price = null;

    public static function tableName()
    {
        return Module::$tablePrefix."catalog_item";
    }

    public function rules(){
        return [
            [['total_amount', 'catalog_id'], 'integer'],
            [['total_amount'], 'default', 'value'=>0],
            [['title', 'uid'], 'string'],
            [['uid'], 'unique'],
            [['active'], 'integer'],
            [['active'], 'default', 'value'=>1],
            [['catalog_id'], 'exist', 'targetRelation'=>'catalog', 'skipOnError'=>false, 'skipOnEmpty'=>true],
            [['catalog_id'], 'default', 'value'=>null]
        ];
    }

    public function attributeLabels()
    {
        return [
            'title'=>Module::t('app', 'title'),
            'uid'=>Module::t('app','uid'),
            'active'=>Module::t('app', 'active'),
            'total_amount'=>Module::t('app', 'total_amount'),
            'catalog_id'=>Module::t('app', 'catalog id'),
            'created_at'=>Module::t('app','created at'),
            'updated_at'=>Module::t('app', 'updated at'),
            'price'=>Module::t('app', 'price')
        ];
    }

    public function getPrice()
    {
        return $this->_price;
    }

    /**
     * @return ItemQuery|\yii\db\ActiveQuery
     */
    public static function find(){
        return new ItemQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCatalog(){
        return $this->hasOne(Catalog::class, ['id'=>'catalog_id']);
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
    public function getAmountStoreById($id)
    {
        $itemStores = ArrayHelper::index($this->itemStores, 'store_id');

        return isset($itemStores[$id]) ? $itemStores[$id]->amount : 0;
    }

    /**
     * Возвращает количество товара на определенном складе
     * @param $id
     * @return int
     */
    public function getAmountStoreByUid($uid)
    {
        $itemStores = ArrayHelper::index($this->itemStores, 'store.uid');

        return isset($itemStores[$uid]) ? $itemStores[$uid]->amount : 0;
    }

    /**
     * Устанавливает цену для определенного товара по типу цены $uid
     * @param $uid
     * @param $price
     * @return bool
     * @throws \yii\base\Exception
     */
    public function setPrice($uid, $price){
        if(!$priceType = PriceType::getByUid($uid)){
            return false;
        }

        $itemPrice = ItemPrice::getOrCreate([
            'price_type_id'=>$priceType->id,
            'item_id'=>$this->id
        ]);

        $itemPrice->price = $price;
        $itemPrice->save();

        return true;
    }

    /**
     * Изменяет количество товара на определенном складе по UID
     * @param $uid
     * @param $amount
     * @throws \yii\base\Exception
     */
    public function setAmountStore($uid, $amount){

        if(!$store = Store::getByUid($uid)){
            return false;
        }

        $itemStore = ItemStore::getOrCreate([
            'store_id'=>$store->id,
            'item_id'=>$this->id
        ]);

        $itemStore->amount = $amount;
        $itemStore->save();

        return true;
    }

    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        Bh::deleteAll(ItemStore::class, ['item_id'=>$this->id]);
        Bh::deleteAll(ItemPrice::class, ['item_id'=>$this->id]);


        return true;
    }

}