<?php

namespace reketaka\helps\modules\basket\bootstrapCallback;

use common\models\BaseHelper;
use reketaka\helps\common\helpers\Bh;
use reketaka\helps\modules\basket\models\Basket;
use reketaka\helps\modules\basket\models\BasketItem;
use Yii;
use yii\base\Event;
use yii\base\Model;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\User;

class BasketCallback extends Model{

    public static function onModifyItem(Event $event){
        /**
         * @var $basketItem BasketItem
         * @var $basket Basket
         */
        $basketItem = $event->sender;
        $basket = $basketItem->basket;


        if($event->name == 'beforeUpdate') {
            $basket->total_amount = $basket->total_amount - $basketItem->getOldAttribute('amount') + $basketItem->amount;
            $basket->total_price = $basket->total_price - ($basketItem->getOldAttribute('amount')*$basketItem->getOldAttribute('price')) + ($basketItem->price*$basketItem->amount);
        }

        if($event->name == 'beforeInsert'){
            $basket->total_amount += $basketItem->amount;
            $basket->total_price += $basketItem->price * $basketItem->amount;
        }

        if($event->name == 'beforeDelete'){
            $basket->total_amount -= $basketItem->amount;
            $basket->total_price -= $basketItem->price * $basketItem->amount;

            $basket->total_amount = $basket->total_amount <= 0?0:$basket->total_amount;
            $basket->total_price = $basket->total_price <= 0?0:$basket->total_price;
        }

        $basket->save();


        return true;
    }

    public static function onFullDeleteBasket(Event $event){
        /**
         * @var $basket Basket
         */
        $basket = $event->sender;

        BasketItem::deleteAll(['basket_id'=>$basket->id]);
    }

    public static function onLoginUser(Event $event){
        /**
         * @var $user User
         */
        $user = $event->identity;

        if(!$userBasket = $user->basket){
            return false;
        }

        $itemsId = BasketItem::find()->innerJoinWith(['basket b'])->where(['b.user_id'=>$user->id])->all();
        $itemsId = ArrayHelper::getColumn($itemsId, 'id');

        if(!$itemsId){
            return false;
        }

        $sql = Yii::$app->db->createCommand()->update(BasketItem::tableName(), [
            'basket_id'=>$userBasket->id
        ], ['id'=>$itemsId])->execute();

        $query = (new Query)
            ->select([
                'id',
                'item_id',
                new Expression("COUNT(item_id) as 'count'"),
                new Expression("SUM(amount) as 'amount'")
            ])
            ->from(BasketItem::tableName())
            ->where(['basket_id'=>$userBasket->id])
            ->groupBy(['item_id'])
            ->having("count > 1");

        $items = $query->createCommand()->queryAll();

        $transaction = Yii::$app->db->beginTransaction();

        foreach($items as $item){

            Yii::$app->db->createCommand()->update(BasketItem::tableName(), ['amount'=>$item['amount']], ['id'=>$item['id']])->execute();
            Yii::$app->db->createCommand()->delete(BasketItem::tableName(), "item_id = {$item['item_id']} AND id != {$item['id']}")->execute();


        }

        $transaction->commit();

        $userBasket->refreshTotals();

        return true;
    }

}