<?php

use kartik\select2\Select2;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\menu\MenuItemRolesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/**
 * @var $menuItems[]
 */


$this->title = Yii::t('app', 'Menu Item Roles');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-item-roles-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'create'), ['create'], ['class' => 'btn btn-success']) ?>
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
                'attribute' => 'menu_item_id',
                'format'=>'raw',
                'content' => function($model){
                    if($menuItem = $model->menuItem){
                        return Html::a($menuItem->title, ['/menu/menu-item/view', 'id'=>$menuItem->id]);
                    }
                },
                'filter' => Select2::widget([
                    'data'=>$menuItems,
                    'attribute' => 'menu_item_id',
                    'model' => $searchModel
                ])
            ],
            'role_name',

            [
                'class' => 'yii\grid\ActionColumn',
                'options'=>[
                    'style'=>'width:75px;'
                ]
            ],
        ],
    ]); ?>
</div>
