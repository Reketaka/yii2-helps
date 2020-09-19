<?php

use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
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

        <div class="col-md-6">
            <?=$form->field($model, 'item_id')->widget(Select2::class, [
                'initValueText' => ArrayHelper::getValue($model, 'item.title'),
                'pluginOptions' => [
                    'allowClear'=>true,
                    'minimumInputLength'=>3,
                    'language'=>[
                        'errorLoading'=>new JsExpression("function(){ return yii.t('app', 'wait_result'); }")
                    ],
                    'ajax' => [
                        'url' => ['/catalog/item/find-by-title'],
                        'dataType' => 'json',
                        //                    'results'=>new JsExpression("function(data){  return {results:data.result} } "),
                        'processResults' => new JsExpression("function(data, params){ return {results:data}  }"),
                        'data' => new JsExpression('function(params) { return {q:params.term}; }'),
                        'cache'=>true
                    ],
                ]
            ])?>
        </div>

        <div class="col-md-6">
            <?=$form->field($model, 'price_type_id')->widget(Select2::class, [
                'data'=>$typePrices,
                'options' => [
                    'placeholder'=>$model->getAttributeLabel('price_type_id')
                ],
                'pluginOptions' => [
                    'allowClear'=>true
                ]
            ])?>
        </div>

    </div>

    <div class="row">
        <div class="col-md-12">
            <?=$form->field($model, 'price')->textInput()?>
        </div>
    </div>


    <?=Html::submitButton(Module::t('app', $model->isNewRecord?"create":"update"), ['class'=>'btn btn-success'])?>

<?php ActiveForm::end()?>
