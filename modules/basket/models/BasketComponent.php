<?php

namespace reketaka\helps\modules\basket\models;

use reketaka\helps\common\models\CommonRecord;
use yii\base\Model;

/**
 * Class Basket
 * @package reketaka\helps\modules\basket\models
 */
class BasketComponent extends Model
{
    private $basket;

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub

        $this->basket = Basket::getBasketUser();
    }

    /**
     * Добавляет в корзину указанную модель товара, модель товара должна реализовывать методы IBasketProduct
     * @param Model $item
     * @param int $amount
     */
    public function put(Model $item, $amount = 1)
    {
        $this->basket->put($item, $amount);
    }

    /**
     * Удаляет товар из корзину либо по модели либо по id товара корзины
     * @param $item
     * @return bool
     */
    public function remove($item){
        if(is_object($item)) {
            $this->basket->remove($item);
            return true;
        }

        $this->basket->removeById($item);
        return true;
    }

    /**
     * Изменяет количество товара в корзину по модели товара
     * @param Model $item
     * @param $amount
     */
    public function modify(Model $item, $amount){
        $this->basket->modify($item, $amount);
    }

    public function refresh()
    {
        $this->basket->refresh();
    }

    /**
     * Возвращает общее количество товаров
     * @return mixed
     */
    public function getTotalAmount(){
        return $this->basket->total_amount;
    }

    /**
     * Возвращает общую стоимость коризны
     * @return mixed
     */
    public function getTotalPrice(){
        return $this->basket->total_price;
    }

    /**
     * Возвращает непосредственно модель текущей корзины
     * @return Basket
     */
    public function getBasketModel(){
        return $this->basket;
    }
}