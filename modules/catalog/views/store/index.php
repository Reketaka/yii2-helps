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


//\common\helpers\BaseHelper::dump(Yii::getAlias('@reketaka/helps/modules/catalog/messages'));

?>

<?=Html::a(Module::t('app', 'create'), ['create'], ['class'=>'btn btn-success'])?>

<?=GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns'=>[
        'id',
        'title',
        'uid',
        'created_at',
        [
            'class' => 'yii\grid\ActionColumn',
            'options'=>[
                'style'=>'width:75px;'
            ],
        ],
    ]
])?>
