<?php

use frontend\models\CartRefresh;
use reketaka\helps\modules\basket\models\BasketItem;
use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var $this View
 * @var BasketItem $basketItem
 * @var $model CartRefresh
 * @var $form ActiveForm
 * @var $basketItemFields[]
 * @var $useProductLink
 */




?>


<div class="item">

    <button class="btn btn-default deleteItem" onclick="app.basket.deleteItem({url:'/emarket/basket/remove/item/23/', id:'23', elem:this});return false;">
        <span class="glyphicon glyphicon-remove"></span>
    </button>

<!--    --><?php //if($mainPhoto instanceof umiImageFile):?>
<!--        <div class="image">-->
<!--            <a href="--><?//=$page->link?><!--">-->
<!--                --><?//=$this->render(
//                    array(
//                        'id' => $page->getId(),
//                        'fieldName' => 'photo',
//                        'src' => $mainPhoto->getFilePath(true),
//                        'empty' => $this->translate('empty-photo'),
//                        'height' => 114
//                    ),
//                    'library/thumbnails'
//                )?>
<!--            </a>-->
<!--        </div>-->
<!--    --><?php //endif; ?>

    <div class="caption">
        <div class="item-name">
            <?php if($useProductLink):?>
                <a href="#"><?=$basketItem->title?></a>
            <?php else: ?>
                <?=$basketItem->title?>
            <?php endif; ?>


        </div>
        <div class="item-desc">
            <?php foreach($basketItemFields as $fieldName):?>
                <small><b><?=$basketItem->getAttributeLabel($fieldName)?></b> <?=$basketItem->$fieldName?></small><BR>
            <?php endforeach; ?>

        </div>
    </div>
    <div class="price-block">
        <div class="itemPrice">
            <span><?=Yii::$app->formatter->asCurrency($basketItem->price)?></span>
        </div>
        <div class="buttons">
            <button type="button" class="btn btn-sm btn-minus" onclick="app.basket.countChange({param:'-', basketId:'23', elem:this});return false;"><span class="glyphicon glyphicon-minus"></span></button>
<!--            <input type="number" name="count" class="form-control" min="1" value="--><?//=$basketItem->amount?><!--">-->

            <?=Html::activeTextInput($model, "items[{$basketItem->id}]", [
                'class'=>['text-center', "form-control"],
                'value'=>$basketItem->amount,
                'type'=>'number',
                'min'=>1,
            ])?>

            <button type="button" class="btn btn-sm btn-plus"  onclick="app.basket.countChange({param:'+', basketId:'23', elem:this});return false;"><span class="glyphicon glyphicon-plus"></span></button>
            <div class="clearfix"></div>
        </div>

    </div>
    <div class="clearfix"></div>
</div>