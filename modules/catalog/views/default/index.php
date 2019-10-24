<?php

use yii\web\View;use yii\widgets\Menu;

/**
 * @var $this View
 */

?>


<div>
    <h3>Все возможности модуля</h3>

    <?=Menu::widget([
        'items' => [
            ['label'=>'Типы цен', 'url'=>['price-type/index']],
            ['label'=>'Товары', 'url'=>['item/index']],
            ['label'=>'Склады', 'url'=>['store/index']],
            ['label'=>'Количество товаров на складах', 'url'=>['item-store/index']],
            ['label'=>'Цены товаров', 'url'=>['item-price/index']]
        ],
    ])?>
</div>
