<?php

namespace reketaka\helps\modules\basket\models;

use common\models\BaseHelper;
use Yii;
use yii\base\BaseObject;
use yii\base\Component;
use yii\base\Model;

/**
 * Class Basket
 * @package reketaka\helps\modules\basket\models
 */
class BasketComponent extends Component
{
    /**
     * @var $basket Basket
     */
    private $basket;

    public $authorization;

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub

        if($this->authorization && !Yii::$app->user->isGuest){
            $this->basket = Basket::getBasketUser();
        }

        if(!$this->authorization && Yii::$app->user->isGuest){
            $this->basket = Basket::getBasketUser();
        }
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

    /**
     * Обновляет модель корзины
     */
    public function refresh()
    {
        $this->basket->refresh();
    }

    /**
     * Возвращает общее количество товаров
     * @return mixed
     */
    public function getTotalAmount(){
        if(!$this->basket){
            return 0;
        }

        return $this->basket->total_amount;
    }

    /**
     * Возвращает общую стоимость коризны
     * @return mixed
     */
    public function getTotalPrice(){
        if(!$this->basket){
            return 0;
        }

        return $this->basket->total_price;
    }

    /**
     * Очищает всю корзину пользователя
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function removeAll(){
        $this->basket->delete();
        $this->init();
    }

    /**
     * Возвращает непосредственно модель текущей корзины
     * @return Basket
     */
    public function getModel(){
        return $this->basket;
    }
}