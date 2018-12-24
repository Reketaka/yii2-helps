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
    ],
    'title',
    'alias',
    'created_at',
    'updated_at',
    [
        'class' => 'yii\grid\ActionColumn',
        'options'=>[
            'style'=>'width:75px;'
        ],
        'buttons' => [

        ],
        'template' => '{view}{update}{delete}',
    ]
];


?>

<div class="row">
    <div class="col-md-12">

        <p><?=Html::a('Create User Group', ['/usermanager/user-group/create'], ['class'=>'btn btn-success'])?></p>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => $attributes
        ]); ?>
    </div>
</div>
