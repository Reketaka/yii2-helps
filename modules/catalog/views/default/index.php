<?php

use yii\web\View;
use yii\widgets\Menu;
use common\helpers\BaseHelper;
use reketaka\helps\modules\basket\models\Basket;

/**
 * @var $this View
 */

$product = \common\models\Item::findOne(1);

Yii::$app->basket->put($product, 10);

Yii::$app->basket->put(\common\models\Item::findOne(2), 5);
Yii::$app->basket->remove(\common\models\Item::findOne(2));

Yii::$app->basket->put(\common\models\Item::findOne(2));
Yii::$app->basket->modify(\common\models\Item::findOne(2), 20);




Yii::$app->basket->modify(\common\models\Item::findOne(2), 1);

Yii::$app->basket->refresh();

BaseHelper::dump(Yii::$app->basket->getTotalAmount());
BaseHelper::dump(Yii::$app->basket->getTotalPrice());



?>


<div>
    <h3>Все возможности модуля</h3>

    <?=Menu::widget([
        'items' => [
            ['label'=>'Типы цен', 'url'=>['price-type/index']],
            ['label'=>'Товары', 'url'=>['item/index']],
            ['label'=>'Склады', 'url'=>['store/index']],
            ['label'=>'Каталоги', 'url'=>['catalog/index']],
            ['label'=>'Количество товаров на складах', 'url'=>['item-store/index']],
            ['label'=>'Цены товаров', 'url'=>['item-price/index']]
        ],
    ])?>
</div>
