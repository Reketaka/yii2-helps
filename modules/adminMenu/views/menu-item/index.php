<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\menu\MenuItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/**
 * @var $sections[]
 */

$this->title = Yii::t('app', 'Menu Items');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-item-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                    'attribute' => 'id',
                    'options' => [
                            'style'=>'width:75px'
                    ]
            ],
            'title',
            'alias',
            'url:url',
            [
                'attribute' => 'section_id',
                'format'=>'raw',
                'content' => function($model){
                    if($section = $model->section){
                        return Html::a($section->title, ['/menu/menu-section/view', 'id'=>$section->id]);
                    }
                },
                'filter' => Select2::widget([
                    'data'=>$sections,
                    'attribute' => 'section_id',
                    'model' => $searchModel
                ])
            ],
            'order',

            [
                'class' => 'yii\grid\ActionColumn',
                'options'=>[
                    'style'=>'width:75px;'
                ]
            ],
        ],
    ]); ?>
</div>
