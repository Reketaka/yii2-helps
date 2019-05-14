<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model reketaka\helps\modules\dictionaries\models\DictionariesValue */
/* @var $form yii\widgets\ActiveForm */
/**
 * @var $dictionaries[]
 */
?>

<div class="dictionaries-value-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'dictionary_id')->widget(Select2::class, [
        'data'=>$dictionaries
    ]) ?>

    <?= $form->field($model, 'value')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
