<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel reketaka\helps\modules\seo\models\SeoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/**
 * @var $controllers[]
 */

$this->title = 'Seos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="seo-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Seo', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'item_id',
            'modelName',
            [
                'attribute' => 'path',
                'format'=>'raw',
                'content' => function($model){
                    return $model->path;
                },
                'filter' => Select2::widget([
                    'attribute' => 'path',
                    'model'=>$searchModel,
                    'data'=>$controllers
                ])
            ],
            //'path',
            //'h1',
            //'title',
            //'keywords',
            //'description',
            'created_at',


            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
