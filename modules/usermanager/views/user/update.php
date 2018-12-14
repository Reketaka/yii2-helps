<?php

/**
 * @var $this View
 * @var $model
 * @var $userEditAttributes[]
 */

use common\helpers\BaseHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;


?>


<div class="user-edit">

    <?php $form = ActiveForm::begin()?>

        <?php foreach($userEditAttributes as $userEditAttribute):?>

            <?=$form->field($model, $userEditAttribute)->textInput()?>

        <?php endforeach; ?>

        <?=Html::submitButton('Save')?>

    <?php ActiveForm::end()?>

</div>
