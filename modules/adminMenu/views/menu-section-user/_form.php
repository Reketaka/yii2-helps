<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\menu\MenuSectionUser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-section-user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_id')->widget(Select2::class, [
        'initValueText' => (($user = $model->user)?$user->username:null),
        'options'=>[
            'placeholder'=>$model->getAttributeLabel('user_id')
        ],
        'pluginOptions' => [
            'allowClear'=>true,
            'minimumInputLength'=>3,
            'ajax'=>[
                'url'=>Url::to(['/adminmenu/menu-base/get-system-user']),
                'dataType'=>'json',
                'data'=>new JsExpression('function(params){return {q:params.term}; }')
            ]
        ]
    ]) ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'order')->textInput() ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'parent')->textInput() ?>
        </div>
    </div>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
