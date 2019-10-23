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


//\common\helpers\BaseHelper::dump(Yii::getAlias('@reketaka/helps/modules/catalog/messages'));

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
