<?php

use common\models\BaseHelper;
use reketaka\helps\modules\catalog\models\PriceType;
use yii\web\View;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use reketaka\helps\modules\catalog\models\ItemSearch;
use yii\helpers\Html;
use reketaka\helps\modules\catalog\models\Item;
use reketaka\helps\modules\catalog\models\Store;
use yii\helpers\ArrayHelper;
use reketaka\helps\modules\catalog\Module;

/**
 * @var $this View
 * @var $dataProvider ActiveDataProvider
 * @var $searchModel ItemSearch
 * @var $stores Store[]
 * @var $priceTypes PriceType[]
 */


?>

<?=Html::a(Module::t('app','create'), ['create'], ['class'=>'btn btn-success'])?>

<div>

    <?=GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function($model){

            if(!$model->active){
                return ['class'=>'danger'];
            }
        },
        'columns' => [
            [
                'attribute'=>'id',
                'options'=>[
                    'style'=>'width:75px;'
                ],
            ],
            [
                'attribute' => 'title',
                'format'=>'raw',
                'content' => function($model){
                    /**
                    * @var $model Item
                    */
                    $text = [];
                    $text[] = $model->getAttributeLabel('id')." ".Html::tag('b', $model->id);
                    $text[] = $model->getAttributeLabel('uid')." ".Html::tag('b', $model->uid);
                    $text[] = $model->getAttributeLabel('title')." ".Html::tag('b', $model->title);

                    $text[] = $model->getAttributeLabel('catalog_id')." ".Html::tag('b', (($catalog = $model->catalog)?Html::a($catalog->title, ['catalog/view', 'id'=>$catalog->id]):Html::tag('label', Module::t('app', 'not_set_catalog'), ['class'=>'label label-danger'])));
                    return implode("<BR>", $text);
                }
            ],
            [
                'attribute'=>'total_amount',
                'options'=>[
                    'style'=>'width:75px;'
                ],
            ],
            [
                'label' => Module::t('app', 'amount_in_stores'),
                'format'=>'raw',
                'content' => function($model)use($stores){
                    /**
                     * @var $model Item
                     */
                    $text = [];
                    foreach($stores as $store){
                        $text[] = Html::tag('b', $store->title).": ".$model->getAmountStoreById($store->id);
                    }

                    return implode("<BR>", $text);
                }
            ],
            [
                'label' => Module::t('app', 'amount_in_prices'),
                'format'=>'raw',
                'content' => function($model)use($priceTypes){
                    /**
                     * @var $model Item
                     */
                    $text = [];
                    foreach($model->prices as $price){

                        $text[] = Html::tag('b', $price->priceType->title).": ".Yii::$app->formatter->asDecimal($price->price);
                    }

                    return implode("<BR>", $text);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'options'=>[
                    'style'=>'width:75px;'
                ]
            ]
        ]
    ])?>

</div>
