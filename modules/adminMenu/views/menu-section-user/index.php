<?php

use common\models\User;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\menu\MenuSectionUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Menu Section Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-section-user-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'rowOptions' => function ($model, $key, $index, $grid) {
            return ['data-sortable-id' => $model->id];
        },
        'filterModel' => $searchModel,
        'options' => [
            'data' => [
                'sortable-widget' => 1,
                'sortable-url' => \yii\helpers\Url::toRoute(['sorting']),
            ]
        ],
        'columns' => [
            [
                'class' => \kotchuprik\sortable\grid\Column::className(),
            ],
            [
                    'attribute' => 'id',
                    'options' => [
                            'style'=>'width:75px'
                    ]
            ],
            'title',
            [
                'attribute' => 'user_id',
                'format'=>'raw',
                'content' => function($model){
                    if($user = $model->user){
                        return $user->username;
                    }
                },
                'filter' => Select2::widget([
                    'attribute' => 'user_id',
                    'model' => $searchModel,
                    'initValueText' => $searchModel->user_id?((($user = User::findOne($searchModel->user_id))?$user->username:'')):'',
                    'pluginOptions' => [
                        'allowClear'=>true,
                        'minimumInputLength'=>3,
                        'ajax'=>[
                            'url'=>Url::to(['/wb/webmaster-site-select/get-system-user']),
                            'dataType'=>'json',
                            'data'=>new JsExpression('function(params){return {q:params.term}; }')
                        ]
                    ]
                ])
            ],
            'order',
            'parent',
            //'created_at',
            //'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'options'=>[
                    'style'=>'width:75px;'
                ]
            ],
        ],
    ]); ?>
</div>
