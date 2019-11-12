<?php

namespace reketaka\helps\modules\basket\models;

use reketaka\helps\common\models\CommonRecord;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class Basket
 * @package reketaka\helps\modules\basket\models
 * @property $id
 * @property $user_id
 * @property $session_id
 * @property $total_price
 * @property $total_amount
 * @property $created_at
 * @property $updated_at
 */
class Basket extends CommonRecord {

    public $behaviorTimestamp = true;

    public static function tableName()
    {
        return "basket";
    }

    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['session_id'], 'string'],
            [['session_id', 'user_id'], 'default', 'value'=>null],
            [['total_amount'], 'integer'],
            [['total_price'], 'safe'],
            [['total_price', 'total_amount'], 'default', 'value'=>0]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems(){
        return $this->hasMany(BasketItem::class, ['basket_id'=>'id']);
    }

    /**
     * @return Basket|boolean
     */
    public static function getBasketUser(){

        $transaction = \Yii::$app->db->beginTransaction();

        try{
            if(!\Yii::$app->user->isGuest && ($basket = Basket::findOneForUpdate(['user_id'=>\Yii::$app->user->getId()]))){
                $transaction->commit();
                return $basket;
            }

            if(\Yii::$app->user->isGuest && ($basket = Basket::findOneForUpdate(['session_id'=>\Yii::$app->session->getId()]))){
                $transaction->commit();
                return $basket;
            }

            $basket = new Basket([
                'user_id'=>\Yii::$app->user->getId(),
                'session_id'=>\Yii::$app->user->isGuest?\Yii::$app->session->getId():null
            ]);

            $basket->save();

            $transaction->commit();
        }catch (\Exception $exception){
            \Yii::error($exception->getMessage(), __METHOD__);
            $transaction->rollBack();
            return false;
        }

        return $basket;
    }

    private function createBasketItem(Model $item){
        $basketModule = \Yii::$app->getModule('basket');

        $transaction = \Yii::$app->db->beginTransaction();

        try{

            if($basketItem = BasketItem::findOneForUpdate(['basket_id'=>$this->id, 'item_id'=>$item->getId()])){
                $transaction->commit();
                return $basketItem;
            }

            $basketItem = new BasketItem([
                'basket_id'=>$this->id,
                'item_id'=>$item->getId(),
                'title'=>$item->getTitle(),
                'uid'=>$item->getBasketField('uid'),
                'price'=>$item->getPrice()
            ]);

            if($basketModule) {
                foreach ($basketModule->basketItemFields as $fieldName) {
                    $basketItem->$fieldName = $item->getBasketField($fieldName);
                }
            }

            $basketItem->save();


            $transaction->commit();
        }catch (\Exception $exception){
            \Yii::error($exception->getMessage());
            $transaction->rollBack();
        }

        return $basketItem;

    }

    public function put(Model $item, $amount){

        if(!$basketItem = $this->createBasketItem($item)){
            return false;
        }

        $basketItem->amount = $amount;
        $basketItem->save();


        return true;
    }

    public function removeById($id){
        if(!$basketItem = $this->getItems()->where(['id'=>$id])->one()){
            return false;
        }

        $basketItem->delete();

        return true;
    }

    public function remove(Model $item){

        if(!$basketItem = $this->getItems()->where(['item_id'=>$item->getId()])->one()){
            return false;
        }

        $basketItem->delete();

        return true;
    }

    public function modify(Model $item, $amount){
        if(!$basketItem = $this->getItems()->where(['item_id'=>$item->getId()])->one()){
            return false;
        }

        $basketItem->amount = $amount;
        $basketItem->save();

        return true;
    }

    /**
     * Возвращает все найденые модели продуктов
     * @return array
     */
    public function getProducts(){
        if(!$productClass = \Yii::$app->getModule('basket')->productClass){
            return [];
        }

        $basketItemsId = $this->getItems()
            ->select('item_id')->asArray()->all();

        if(!$basketItemsId = ArrayHelper::getColumn($basketItemsId, 'item_id')){
            return [];
        }

        $products = $productClass::find()->where(['id'=>$basketItemsId])->all();

        return $products;
    }

}