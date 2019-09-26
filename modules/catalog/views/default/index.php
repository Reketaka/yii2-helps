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
            ['label'=>'Типы цен', 'url'=>['/catalog/price-type/index']],
            ['label'=>'Товары', 'url'=>['/catalog/item/index']]
        ],
    ])?>
</div>
