<?php

namespace reketaka\helps\modules\catalog\backend\models;

use reketaka\helps\common\helpers\Bh;
use reketaka\helps\modules\catalog\exceptions\SaveItemModelException;
use reketaka\helps\modules\catalog\models\Item;
use reketaka\helps\modules\catalog\models\ItemPrice;
use Yii;
use yii\base\Exception;
use function array_key_exists;
use function array_map;
use function array_merge;

class ItemUpdateModel extends Item{

    public $prices = [];

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['prices'], 'each', 'rule'=>['double']]
        ]);
    }

    /**
     * Загружает цены товара
     */
    public function loadPrices(){

        $priceTypes = $this->module->modelsPath['PriceType']::find()->indexBy('id')->select(['id'])->asArray()->all();
        $priceTypes = array_map(function($m){
            return 0;
        }, $priceTypes);

        foreach($this->getPrices()->all() as $priceItem){
            /**
             * @var ItemPrice $priceItem
             */
            if(!array_key_exists($priceItem->price_type_id, $priceTypes)){
                continue;
            }

            $priceTypes[$priceItem->price_type_id] = $priceItem->price;
        }


        $this->prices = $priceTypes;
    }

    private function savePrices(){

        $transaction = Yii::$app->db->beginTransaction();

        try{
            ItemPrice::deleteAll(['item_id'=>$this->id]);

            foreach($this->prices as $priceId=>$priceValue):

                $m = new ItemPrice([
                    'item_id'=>$this->id,
                    'price_type_id'=>$priceId,
                    'price'=>$priceValue
                ]);

                $m->save();

                if($m->hasErrors()){
                    throw new SaveItemModelException("Can't save priceItem Model");
                }

            endforeach;

            $transaction->commit();
        }catch (\Exception $exception){

            $transaction->rollBack();
            return false;
        }

        return true;
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        if(!parent::save($runValidation, $attributeNames)){
            return false;
        }

        $this->savePrices();


        return true;
    }

}