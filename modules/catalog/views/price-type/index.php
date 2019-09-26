<?php

use reketaka\helps\modules\catalog\models\PriceTypeSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var $this View
 * @var $searchModel PriceTypeSearch
 * @var $dataProvider ActiveDataProvider
 */

?>

<?=GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns'=>[
        'id',
        'title',
        'alias',
        'uid',
        [
            'attribute'=>'default',
            'format'=>'raw',
            'content'=>function($model){
                if($model->default){
                    return Html::tag('span', null, ['class'=>'glyphicon glyphicon-ok']);
                }

                return Html::tag('span', null, ['class'=>'glyphicon glyphicon-remove']);
            }
        ],
        'created_at',
        [
            'class' => 'yii\grid\ActionColumn',
            'options'=>[
                'style'=>'width:75px;'
            ],
        ],
    ]
])?>
