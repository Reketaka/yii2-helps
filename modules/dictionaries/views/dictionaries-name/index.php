<?php

use common\helpers\BaseHelper;
use reketaka\helps\modules\dictionaries\models\DictionariesHelper;
use reketaka\helps\modules\dictionaries\models\DictionariesValue;
use reketaka\helps\modules\dictionaries\models\DictionariesValueSearch;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel reketaka\helps\modules\dictionaries\models\DictionariesNameSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/**
 * @var $searchModelValue
 */


$this->title = 'Dictionaries Names';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dictionaries-name-index">

    <p>
        <?= Html::a('Create Dictionaries Name', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id',
                'options' => [
                    'style'=>'width:75px'
                ]
            ],
            'title',
            'alias',
            'description',
            [
                'label'=>'Count Values',
                'format'=>'raw',
                'content'=>function($model)use($searchModelValue){

                    $url = Url::to(['/dc/dictionaries-value/index'])."?".http_build_query([
                            Html::getInputName($searchModelValue, 'dictionary_id')=>$model->id
                    ]);


                    return Html::tag('span', $model->getDictionariesValues()->count(), ['class'=>'badge'])." ".Html::a("Просмотр", $url, ['class'=>'btn btn-success btn-xs']);
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
