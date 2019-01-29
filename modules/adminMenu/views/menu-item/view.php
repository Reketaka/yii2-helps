<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\menu\MenuItem */
/**
 * @var $roles[]
 */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Menu Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-item-view">

    <p>
        <?= Html::a(Yii::t('app', 'update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
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
            'title',
            'alias',
            'url:url',
            [
                'attribute'=>'section_id',
                'format'=>'raw',
                'value'=>function()use($model){
                    if($section = $model->section){
                        return Html::a($section->title, ['/menu/menu-section/view', 'id'=>$section->id]);
                    }
                }
            ],
            'order',
        ]
    ]);
 
   ?>

    <?php if($roles):?>
        <div class="col-md-4">
            <h4>Роли доступа к элементу меню</h4>
            <div class="list-group">
                <?php foreach($roles as $role):?>
                    <?=Html::a($role->role_name, ['/menu/menu-item-roles/view', 'id'=>$role->id], ['class'=>'list-group-item'])?>
                <?php endforeach; ?>
            </div>
        </div>

    <?php endif; ?>

</div>
