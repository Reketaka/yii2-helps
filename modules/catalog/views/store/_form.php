<?php

use yii\web\View;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use reketaka\helps\modules\catalog\Module;

/**
 * @var $this View
 * @var $model \reketaka\helps\modules\catalog\models\Store
 */

?>

<?php $form = ActiveForm::begin()?>

<div class="row">

    <div class="col-md-6">
        <?=$form->field($model, 'title')->textInput()?>
    </div>

    <div class="col-md-6">
        <?=$form->field($model, 'uid')->textInput()?>
    </div>

</div>

<div class="row">

    <div class="col-md-12">
        <?=$form->field($model, 'comment')->textInput()?>
    </div>
</div>



<?=Html::submitButton(Module::t('app', $model->isNewRecord?"create":"update"), ['class'=>'btn btn-success'])?>

<?php ActiveForm::end()?>
