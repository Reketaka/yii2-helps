<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\menu\MenuItemUser */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Menu Item Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-item-user-view">

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
            [
                'attribute'=>'menu_item_id',
                'format'=>'raw',
                'value'=>function()use($model){
                    if($menuItem = $model->menuItem){
                        return Html::a($menuItem->title, ['/menu/menu-item/view', 'id'=>$menuItem->id]);
                    }
                }
            ],
            [
                'attribute'=>'menu_section_id',
                'format'=>'raw',
                'value'=>function()use($model){
                    if($menuSection = $model->menuSection){
                        return Html::a($menuSection->title, ['/menu/menu-section/view', 'id'=>$menuSection->id]);
                    }
                }
            ],
            'order',
        ]
    ]);
 
   ?>

</div>
