<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\menu\MenuItemRoles */
/* @var $form yii\widgets\ActiveForm */
/**
 * @var $menuItems[]
 */

?>

<div class="menu-item-roles-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'menu_item_id')->widget(Select2::class, [
        'data'=>$menuItems
    ]) ?>

    <?= $form->field($model, 'role_name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
