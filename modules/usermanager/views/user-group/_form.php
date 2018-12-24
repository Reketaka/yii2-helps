<?php

use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/**
 * @var $this View
 */

?>


<?php $form = ActiveForm::begin()?>

    <?=$form->field($model, 'title')->textInput()?>

    <?=$form->field($model, 'alias')->textInput()?>

    <?=Html::submitButton($model->isNewRecord?'Create':'Save', ['class'=>'btn btn-success'])?>

<?php ActiveForm::end()?>
