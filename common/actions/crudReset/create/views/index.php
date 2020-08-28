<?php

use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use kartik\select2\Select2;
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
 * @var $dateAttributes
 * @var $selectAttributes
 * @var $optionalsClosure
 * @var $renderMethod
 */


?>


<div class="update-box pd-30">
    
    
    <div class="update-box-content">

        <?php $form = ActiveForm::begin()?>

            <?php foreach($columns as $column):

                if(in_array($column, $booleanAttributes)) {
                    echo $form->field($model, $column)->checkbox();
                }elseif(in_array($column, $dateAttributes)){
                    echo $form->field($model, $column)->widget(DatePicker::classname(), [
                        'options' => [
                            'placeholder' => $model->getAttributeLabel($column),
                        ],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'todayHighlight' => true,
                            'format' => 'yyyy-mm-dd'
                        ]
                    ]);
                }elseif(array_key_exists($column, $selectAttributes)){


                    $items = $selectAttributes[$column];
                    if($optionalsClosure instanceof \Closure){
                        $items = $optionals[$selectAttributes[$column]];
                    }

                    echo $form->field($model, $column)->widget(Select2::class, [
                        'data'=>$items
                    ]);
                }else{

                    echo $form->field($model, $column)->textInput();
                }

            endforeach; ?>

            <div class="form-group">
                <?=Html::submitButton(Yii::t('app', $model->isNewRecord?'create':'update'), ['class'=>'btn btn-success'])?>
            </div>


        <?php ActiveForm::end()?>

    </div>
    
</div>
