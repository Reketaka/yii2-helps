<?php

use reketaka\helps\modules\catalog\models\PriceTypeSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use reketaka\helps\modules\catalog\Module;

/**
 * @var $this View
 * @var $searchModel PriceTypeSearch
 * @var $dataProvider ActiveDataProvider
 */

?>

<?=Html::a(Module::t('app', 'create'), ['create'], ['class'=>'btn btn-success'])?>
<p></p>

<?=GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns'=>[
        'id',
        'title',
        'alias',
        'uid',
        [
            'class'=>'reketaka\helps\common\widgets\enableColumn\EnableColumn',
            'enableAttributeName' => 'default',
            'attributeToggle'=>true,
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
