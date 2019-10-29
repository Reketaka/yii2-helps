<?php

use reketaka\helps\modules\catalog\models\PriceTypeSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use reketaka\helps\modules\catalog\Module;
use kartik\select2\Select2;

/**
 * @var $this View
 * @var $searchModel PriceTypeSearch
 * @var $dataProvider ActiveDataProvider
 * @var $stores[]
 */


//\common\helpers\BaseHelper::dump(Yii::getAlias('@reketaka/helps/modules/catalog/messages'));

?>

<?=Html::a(Module::t('app', 'create'), ['create'], ['class'=>'btn btn-success'])?>

<?=GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns'=>[
        'id',
        [
            'attribute'=>'item_id',
            'format'=>'raw',
            'content'=>function($model){
                if($item = $model->item){
                    return Html::a($item->title, ['item/view', 'id'=>$item->id]);
                }
            }
        ],
        [
            'attribute'=>'store_id',
            'format'=>'raw',
            'content'=>function($model){
                if($store = $model->store){
                    return Html::a($store->title, ['store/view', 'id'=>$store->id]);
                }
            },
            'filter'=>Select2::widget([
                'data'=>$stores,
                'attribute'=>'store_id',
                'model'=>$searchModel
            ])
        ],
        'amount',
        [
            'class' => 'yii\grid\ActionColumn',
            'options'=>[
                'style'=>'width:75px;'
            ],
        ],
    ]
])?>
