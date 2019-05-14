<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model reketaka\helps\modules\dictionaries\models\DictionariesValue */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Dictionaries Values', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="dictionaries-value-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

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
        'attributes'=>[
            'id',
            [
                'attribute'=>'dictionary_id',
                'format'=>'raw',
                'value'=>function()use($model){
                    if($dictionary = $model->dictionary){
                        return Html::a($dictionary->title, ['/dc/dictionaries-name/view', 'id'=>$dictionary->id]);
                    }
                },
                'alias'
            ],
            'value'
        ]
    ]) ?>


</div>
