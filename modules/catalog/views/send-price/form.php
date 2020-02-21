<?php

use reketaka\helps\common\helpers\Bh;
use yii\db\ActiveRecord;
use yii\bootstrap4\ActiveForm;
use yii\web\View;
use yii\bootstrap4\Html;
use unclead\multipleinput\MultipleInput;
use kartik\select2\Select2;

/**
 * @var $this View
 * @var $model ActiveRecord
 * @var $columns
 * @var $optionals[]
 */

//Bh::dd($model->emails);
?>


<div class="update-box pd-30">


    <div class="update-box-content">

        <?php $form = ActiveForm::begin()?>

            <?php

                echo $form->field($model, 'emails')->widget(MultipleInput::className(), [
                    'max'               => 60,
                    'min'               => 1, // should be at least 2 rows
                    'allowEmptyList'    => false,
                    'enableGuessTitle'  => true,
                    'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header
                ])
                    ->label(false);
            ?>

            <?=$form->field($model, 'email_header')->textInput()?>
            <?=$form->field($model, 'email_content')->textInput()?>

            <?=$form->field($model, 'round_price')->checkbox()?>
            <?=$form->field($model, 'with_active')->checkbox()?>
            <?=$form->field($model, 'active')->checkbox()?>
            <?=$form->field($model,'discount_id')->widget(Select2::class, [
                'data'=>$optionals['discounts'],
                'options' => [
                    'allowClear'=>true
                ]
            ])?>
        

        <div class="form-group">
            <?=Html::submitButton(Yii::t('app', $model->isNewRecord?'insert':'update'), ['class'=>'btn btn-success'])?>
        </div>


        <?php ActiveForm::end()?>

    </div>

</div>
