<?php

use yii\web\View;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var $this View
 */


?>

<?php $form = ActiveForm::begin()?>

    <div class="row">

        <div class="col-md-6">
            <?=$form->field($model, 'title')->textInput()?>
        </div>

        <div class="col-md-6">
            <?=$form->field($model, 'alias')->textInput()?>
        </div>

    </div>

    <div class="row">
        <div class="col-md-6">
            <?=$form->field($model, 'uid')->textInput()?>
        </div>

        <div class="col-md-6">
            <?=$form->field($model, 'description')->textInput()?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <?=$form->field($model, 'default')->checkbox()?>
        </div>
    </div>


    <?=Html::submitButton(Yii::t('app', $model->isNewRecord?"create":"update"), ['class'=>'btn btn-success'])?>

<?php ActiveForm::end()?>
