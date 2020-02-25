<?php

use reketaka\helps\modules\basket\models\CartRefresh;
use reketaka\helps\common\helpers\Bh;
use reketaka\helps\modules\basket\models\BasketComponent;
use yii\web\View;
use reketaka\helps\modules\basket\Module;
use yii\widgets\ActiveForm;

/**
 * @var $this View
 * @var $basket BasketComponent
 * @var $basketItems BasketItem[]
 * @var $model CartRefresh
 * @var $stores[]
 * @var $basketItemFields[]
 * @var $useProductLink
 */

?>

<?php if(!$basketItems):?>
    <h4 class="empty-content">Корзина пуста</h4>
    <p><a href="#">Вернуться в каталог</a></p>
<?php endif; ?>


<?php if($basketItems):?>
    <?php $form = ActiveForm::begin()?>

        <div class="basket">

            <?php foreach($basketItems as $basketItem):?>
                <?=$this->render('components/cartItem', [
                    'basketItem'=>$basketItem,
                    'model'=>$model,
                    'form'=>$form,
                    'basketItemFields'=>$basketItemFields,
                    'useProductLink'=>$useProductLink
                ])?>
            <?php endforeach; ?>


            <div class="item total">

                <div class="price-block">
                    <div class="itemPrice">
                        <span id="basketAllPrice"><?=Yii::$app->formatter->asCurrency($basket->getTotalPrice())?></span>
                        <button type="submit" class="btn btn-info"><span class="glyphicon glyphicon-refresh"></span> <?=Module::t('app', 'refresh')?></button>
                    </div>
                </div>

                <div class="summary-block">
                    <span><?=Module::t('app', 'summary')?></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12` col-sm-12 text-center mt10">
                <a href="#" class="btn btn-warning btn-lg"><span class="glyphicon glyphicon-ok"></span> <?=Module::t('app', 'createOrder')?></a>
            </div>
        </div>

        <p></p>

    <?php ActiveForm::end()?>

<?php endif; ?>

