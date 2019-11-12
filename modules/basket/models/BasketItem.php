<?php

namespace reketaka\helps\modules\basket\models;


use reketaka\helps\common\models\CommonRecord;

/**
 * Class BasketItem
 * @package reketaka\helps\modules\basket\models
 * @property $basket_id
 * @property $item_id
 * @property $title
 * @property $uid
 * @property $price
 * @property $amount
 * @property $created_at
 * @property $updated_at
 */
class BasketItem extends CommonRecord{

    public $behaviorTimestamp = true;

    public static function tableName()
    {
        return "basket_item";
    }

    public function rules()
    {
        return [
            [['basket_id', 'item_id', 'amount'], 'integer'],
            [['price'], 'safe'],
            [['price', 'amount'], 'default', 'value'=>0],
            [['title', 'uid'], 'string'],
            [['title', 'uid', 'item_id'], 'default', 'value'=>null]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBasket(){
        return $this->hasOne(Basket::class, ['id'=>'basket_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct(){
        $productClass = \Yii::$app->getModule('basket')->productClass;
        return $this->hasOne($productClass, ['id'=>'item_id']);
    }

    /**
     * Возвращает итоговую стоимость товарной позиции
     * @return float|int
     */
    public function getTotalPrice(){
        return $this->amount*$this->price;
    }



}