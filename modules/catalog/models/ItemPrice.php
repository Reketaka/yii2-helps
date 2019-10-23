<?php

namespace reketaka\helps\modules\catalog\models;

use reketaka\helps\modules\catalog\Module;

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
            'title'=>Module::t('app', 'title'),
            'alias'=>\Yii::t('app','alias'),
            'description'=>\Yii::t('app', 'description'),
            'uid'=>Module::t('app', 'uid')
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