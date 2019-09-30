<?php

use yii\web\View;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use reketaka\helps\modules\catalog\models\ItemSearch;
use yii\helpers\Html;
use reketaka\helps\modules\catalog\models\Item;
use reketaka\helps\modules\catalog\models\Store;
use yii\helpers\ArrayHelper;

/**
 * @var $this View
 * @var $dataProvider ActiveDataProvider
 * @var $searchModel ItemSearch
 * @var $stores Store[]
 */

?>

<?=Html::a(Yii::t('app','create'), ['create'], ['class'=>'btn btn-success'])?>

<div>

    <?=GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
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
                    return implode("<BR>", $text);
                }
            ],
            'total_amount',
            [
                'label' => Yii::t('app', 'amount_in_stores'),
                'format'=>'raw',
                'content' => function($model)use($stores){
                    /**
                     * @var $model Item
                     */
                    $text = [];
                    foreach($stores as $store){
                        $text[] = Html::tag('b', $store->title).":".$model->getAmountStoreById($store->id);
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
