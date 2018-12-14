<?php

use common\helpers\BaseHelper;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\Html;


/**
 * @var $dataProvider ActiveDataProvider
 * @var $searchModel
 * @var $userIndexAttributes[]
 */


$attributes = [
    [
        'attribute' => 'id',
        'options' => [
            'style'=>'width:75px'
        ]
    ]
];

$attributes = array_merge($attributes, $userIndexAttributes);

$attributes[] = [
    'class' => 'yii\grid\ActionColumn',
    'options'=>[
        'style'=>'width:75px;'
    ],
    'buttons' => [

    ],
    'template' => '{view}{update}{delete}',
    'visibleButtons' => [
        'view'=>Yii::$app->user->can('viewSitePerm'),
        'delete'=>Yii::$app->user->can('deleteSitePerm'),
        'update'=>Yii::$app->user->can('updateSitePerm') || Yii::$app->user->can('updateSiteLevel1Perm'),
    ]
];

?>


<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => $attributes
]); ?>