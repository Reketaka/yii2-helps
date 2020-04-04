<?php

namespace reketaka\helps\modules\basket\models;

use reketaka\helps\modules\basket\models\BasketComponent;
use reketaka\helps\modules\basket\models\BasketItem;
use reketaka\helps\modules\basket\traits\ModuleTrait;
use reketaka\helps\modules\catalog\models\Item;
use Yii;
use yii\base\Model;

class CartRefresh extends Model{

    use ModuleTrait;

    public $items;
    /**
     * @var $basket BasketComponent
     */
    private $basket;

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $this->basket = Yii::$app->basket;
    }

    public function rules()
    {
        return [
            [['items'], 'validateAmounts'],
        ];
    }

    public function validateAmounts($attribute){

        $module = $this->getModule();
        $basketItemClass = $module->basketItemClass;

        $dataItems = $this->$attribute;

        foreach($dataItems as $basketItemId=>$amount){

            if(!$basketItem = $basketItemClass::findOne($basketItemId)){
                continue;
            }

            /**
             * @var $product Item
             */
            if(!$product = $basketItem->product){
                continue;
            }

            if(!$module->canAddMoreThenHas) {
                if ($amount > $product->getTotalAmount()) {
                    $this->basket->modify($product, $product->total_amount);
                    continue;
                }
            }

            if($amount <= 1){
                $this->basket->modify($product, 1);
                continue;
            }

            $this->basket->modify($product, $amount);

        }

        $this->basket->refresh();

    }

    public function refresh(){
        if(!$this->validate()){
            return false;
        }


        return true;
    }

}