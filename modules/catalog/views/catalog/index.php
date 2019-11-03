<?php

use reketaka\helps\modules\catalog\models\Catalog;
use reketaka\helps\modules\catalog\Module;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use kartik\select2\Select2;

/**
 * @var $this View
 * @var $searchModel \reketaka\helps\modules\catalog\models\ItemPriceSearch
 * @var $dataProvider ActiveDataProvider
 */


?>

<?=Html::a(Yii::t('app', 'create'), ['create'], ['class'=>'btn btn-success'])?>

<?=GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns'=>[
        'id',
        'title',
        'alias',
        'uid',
        [
            'attribute' => 'parent_id',
            'format'=>'raw',
            'content'=>function($model){
                if($model->parent_id == Catalog::ROOT_CATALOG_ID){
                    return Html::tag('label', Module::t('app', 'root_catalog'), ['class'=>'label label-default']);
                }
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
