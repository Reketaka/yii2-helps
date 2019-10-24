<?php

namespace reketaka\helps\modules\catalog\models;

use reketaka\helps\modules\catalog\Module;

/**
 * Class ItemPrice
 * @package reketaka\helps\modules\catalog\models
 *
 * @property $id
 * @property $item_id
 * @property $price_type_id
 * @property $price
 * @property $created_at
 * @property $updated_at
 *
 * @property $item Item
 * @property $priceType PriceType
 */
class ItemPrice extends BaseModel {

    public $behaviorTimestamp = true;

    public static function tableName()
    {
        return Module::$tablePrefix."item_price";
    }

    public function rules(){
        return [
            [['item_id', 'price_type_id'], 'required'],
            [['item_id', 'price_type_id'], 'integer'],
            [['price'], 'double'],
            [['price'], 'default', 'value'=>0],
            [['item_id'], 'exist', 'targetRelation'=>'item', 'skipOnError'=>false, 'skipOnEmpty'=>false],
            [['price_type_id'], 'exist', 'targetRelation'=>'priceType', 'skipOnError'=>false, 'skipOnEmpty'=>false]
        ];
    }

    public function attributeLabels()
    {
        return [
            'item_id'=>Module::t('app', 'item_id'),
            'price_type_id'=>Module::t('app', 'price_type_id'),
            'price'=>Module::t('app', 'price'),
            'created_at'=>Module::t('app', 'created at'),
            'updated_at'=>Module::t('app', 'updated at'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem(){
        return $this->hasOne(Item::class, ['id'=>'item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPriceType(){
        return $this->hasOne(PriceType::class, ['id'=>'price_type_id']);
    }

}