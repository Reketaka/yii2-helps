<?php

use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use kartik\select2\Select2;

/**
 * @var $this View
 * @var $searchModel \reketaka\helps\modules\catalog\models\ItemPriceSearch
 * @var $dataProvider ActiveDataProvider
 * @var $typePrices[]
 */


?>

<?=Html::a(Yii::t('app', 'create'), ['create'], ['class'=>'btn btn-success'])?>

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
            'attribute'=>'price_type_id',
            'format'=>'raw',
            'content'=>function($model){
                if($priceType = $model->priceType){
                    return Html::a($priceType->title, ['price-type/view', 'id'=>$priceType->id]);
                }
            },
            'filter'=>Select2::widget([
                'data'=>$typePrices,
                'attribute'=>'price_type_id',
                'model'=>$searchModel
            ])
        ],
        [
            'attribute'=>'price',
            'format'=>'raw',
            'content'=>function($model){
                return Yii::$app->formatter->asDecimal($model->price);
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
