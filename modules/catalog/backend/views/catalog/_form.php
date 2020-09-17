<?php

use yii\web\View;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use reketaka\helps\modules\catalog\Module;
use kartik\select2\Select2;

/**
 * @var $this View
 * @var $typePrices[]
 */


?>

<?php $form = ActiveForm::begin()?>

    <div class="row">

        <div class="col-md-4">
            <?=$form->field($model, 'title')->textInput()?>
        </div>

        <div class="col-md-4">
            <?=$form->field($model, 'alias')->textInput()?>
        </div>

        <div class="col-md-4">
            <?=$form->field($model, 'uid')->textInput()?>
        </div>

    </div>

    <div class="row">
        <div class="col-md-4">
            <?=$form->field($model, 'parent_id')->textInput()?>
        </div>
        <div class="col-md-4">
            <?=$form->field($model, 'active')->checkbox()?>
        </div>
    </div>


    <?=Html::submitButton(Module::t('app', $model->isNewRecord?"create":"update"), ['class'=>'btn btn-success'])?>

<?php ActiveForm::end()?>
