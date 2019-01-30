<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\menu\MenuItemUser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-item-user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'menu_item_id')->textInput() ?>

    <?= $form->field($model, 'menu_section_id')->textInput() ?>

    <?=$form->field($model, 'user_id')->widget(Select2::class, [
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

    <?= $form->field($model, 'order')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
