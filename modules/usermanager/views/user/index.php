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
];

echo Html::a(Yii::t('app', 'create'), ['/usermanager/user/create'], ['class'=>'btn btn-success']);

?>


<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => $attributes
]); ?>