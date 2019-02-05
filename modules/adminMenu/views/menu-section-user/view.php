<?php

use common\helpers\BaseHelper;
use yii\helpers\Html;
use kartik\detail\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\menu\MenuSectionUser */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Menu Section Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="menu-section-user-view">

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
            [
                'attribute'=>'user_id',
                'format'=>'raw',
                'value'=>function()use($model){
                    if($user = $model->user){
                        return Html::a($user->username, ['/usermanager/user/view', 'id'=>$user->id]);
                    }
                }
            ],
            'order',
            'parent',
            'created_at',
            'updated_at',
        ]
    ]);

    if($menuItems):
        echo Html::beginTag('div', ['class'=>'col-md-4']);

        echo Html::tag('h4', 'Элементы раздела');
        echo Html::beginTag('div', ['class'=>'list-group']);

        foreach($menuItems as $menuItem):

            echo Html::beginTag('a', ['class'=>'list-group-item', 'href'=>Url::to(['/adminmenu/menu-item/view', 'id'=>$menuItem->id])]);
            echo $menuItem->title;
            echo Html::endTag('a');

        endforeach;

        echo Html::endTag('div');

        echo Html::endTag('div');

    endif;
 
   ?>


</div>
