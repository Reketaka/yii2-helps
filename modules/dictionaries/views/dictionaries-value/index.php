<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel reketaka\helps\modules\dictionaries\models\DictionariesValueSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/**
 * @var $dictionaries[]
 */

$this->title = 'Dictionaries Values';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dictionaries-value-index">


    <p>
        <?= Html::a('Create Dictionaries Value', ['create'], ['class' => 'btn btn-success']) ?>
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
            [
                'attribute' => 'dictionary_id',
                'format'=>'raw',
                'content' => function($model){
                    if($dictionary = $model->dictionary){
                        return Html::a($dictionary->title, ['/dc/dictionaries-name/view', 'id'=>$dictionary->id]);
                    }
                },
                'filter' => Select2::widget([
                    'data'=>$dictionaries,
                    'attribute' => 'dictionary_id',
                    'model'=>$searchModel
                ])
            ],
            'value',
            'alias',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
