<?php

use reketaka\helps\common\helpers\Bh;
use yii\db\ActiveRecord;
use yii\bootstrap4\ActiveForm;
use yii\web\View;
use yii\bootstrap4\Html;

/**
 * @var $this View
 * @var $model ActiveRecord
 * @var $columns
 * @var $optionals
 * @var $booleanAttributes
 */

?>


<div class="update-box pd-30">
    
    
    <div class="update-box-content">

        <?php $form = ActiveForm::begin()?>

            <?php foreach($columns as $column):

                if(in_array($column, $booleanAttributes)){
                    echo $form->field($model, $column)->checkbox();
                }else {
                    echo $form->field($model, $column)->textInput();
                }

            endforeach; ?>

            <div class="form-group">
                <?=Html::submitButton(Yii::t('app', $model->isNewRecord?'create':'update'), ['class'=>'btn btn-success'])?>
            </div>


        <?php ActiveForm::end()?>

    </div>
    
</div>
