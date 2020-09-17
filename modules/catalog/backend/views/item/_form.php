<?php

use common\models\BaseHelper;
use reketaka\helps\common\helpers\Bh;
use reketaka\helps\modules\catalog\models\PriceType;
use yii\helpers\ArrayHelper;
use yii\web\View;
use kartik\form\ActiveForm;
use reketaka\helps\modules\catalog\models\Item;
use yii\helpers\Html;
use reketaka\helps\modules\catalog\Module;

/**
 * @var $this View
 * @var $model Item
 * @var $priceTypes PriceType[]
 */


?>


<?php $form = ActiveForm::begin()?>

    <div class="row">

        <div class="col-md-3">
            <?=$form->field($model, 'title')->textInput()?>
        </div>

        <div class="col-md-3">
            <?=$form->field($model, 'uid')->textInput()?>
        </div>

        <div class="col-md-3">
            <?=$form->field($model, 'total_amount')->textInput()?>
        </div>

        <div class="col-md-3">
            <?=$form->field($model, 'catalog_id')->textInput()?>
        </div>

    </div>

    <div class="row">
        <div class="col-md-12">
            <?=$form->field($model, 'active')->checkbox()?>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h6><?=Module::t('app', 'amount_in_prices')?></h6>

            <div class="row">
                <?php foreach($priceTypes as $priceType):?>
                    <div class="col-4">
                        <div class="form-group">
                            <label><?=$priceType->title?></label>
                            <?=Html::activeTextInput($model, 'prices', [
                                'value'=>ArrayHelper::getValue($model->prices, $priceType->id, 0),
                                'class'=>['form-control'],
                                'name'=>Html::getInputName($model, 'prices')."[$priceType->id]"
                            ])?>
                        </div>
                    </div>

                <?php endforeach; ?>
            </div>

        </div>


    </div>




<?=Html::submitButton(Module::t('app', $model->isNewRecord?"create":"update"), ['class'=>'btn btn-success'])?>

<?php ActiveForm::end()?>






