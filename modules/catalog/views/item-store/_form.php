<?php

use yii\web\View;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use reketaka\helps\modules\catalog\Module;
use kartik\select2\Select2;

/**
 * @var $this View
 * @var $stores
 */


?>

<?php $form = ActiveForm::begin()?>

    <div class="row">

        <div class="col-md-6">
            <?=$form->field($model, 'item_id')->textInput()?>
        </div>

        <div class="col-md-6">
            <?=$form->field($model, 'store_id')->widget(Select2::class, [
                'data'=>$stores,
                'options' => [
                    'placeholder'=>$model->getAttributeLabel('store_id')
                ],
                'pluginOptions' => [
                    'allowClear'=>true
                ]
            ])?>
        </div>

    </div>

    <div class="row">
        <div class="col-md-12">
            <?=$form->field($model, 'amount')->textInput()?>
        </div>
    </div>


    <?=Html::submitButton(Module::t('app', $model->isNewRecord?"create":"update"), ['class'=>'btn btn-success'])?>

<?php ActiveForm::end()?>
