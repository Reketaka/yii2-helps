<?php

use common\models\BaseHelper;
use yii\web\View;
use kartik\form\ActiveForm;
use reketaka\helps\modules\catalog\models\Item;
use yii\helpers\Html;
use reketaka\helps\modules\catalog\Module;

/**
 * @var $this View
 * @var $model Item
 * @var $fields[]
 */


?>


<?php $form = ActiveForm::begin()?>

    <div class="row">

        <div class="col-md-4">
            <?=$form->field($model, 'title')->textInput()?>
        </div>

        <div class="col-md-4">
            <?=$form->field($model, 'uid')->textInput()?>
        </div>

        <div class="col-md-4">
            <?=$form->field($model, 'total_amount')->textInput()?>
        </div>

    </div>

    <?php if($fields):?>
        <div class="row">
            <?php foreach($fields as $fieldName):?>
                <div class="col-md-3">
                    <?=$form->field($model, $fieldName)->textInput()?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>


<?=Html::submitButton(Module::t('app', $model->isNewRecord?"create":"update"), ['class'=>'btn btn-success'])?>

<?php ActiveForm::end()?>






