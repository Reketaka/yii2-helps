<?php

use yii\web\View;
use kartik\detail\DetailView;
use yii\bootstrap4\Html;

/**
 * @var $this View
 * @va $model
 * @var $columns
 */

?>

<div class="view-box">
    
    <div class="view-box-actions">

        <?= Html::a(Yii::t('app', 'update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
        <p></p>
        
    </div>

    <div class="view-box-content">
    
        <?=DetailView::widget([
            'model'=>$model,
            'condensed'=>true,
            'hover'=>true,
            'mode'=>DetailView::MODE_VIEW,
            'enableEditMode' => false,
            'panel'=>[
                'heading'=>$this->title,
                'type'=>DetailView::TYPE_INFO,
            ],
            'attributes'=>$columns
        ]);
        
        ?>
    
    </div>

</div>