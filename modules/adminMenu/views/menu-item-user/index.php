<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\menu\MenuItemUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Menu Item Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-item-user-index">

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
                        return Html::a($menuItem->title, ['/adminmenu/menu-item/view', 'id'=>$menuItem->id]);
                    }
                }
            ],
            [
                'attribute' => 'menu_section_id',
                'format'=>'raw',
                'content' => function($model){
                    if($menuSection = $model->menuSection){
                        return Html::a($menuSection->title, ['/adminmenu/menu-section-user/view', 'id'=>$menuSection->id]);
                    }
                }
            ],
            [
                'attribute' => 'user_id',
                'format'=>'raw',
                'content' => function($model){
                    if($user = $model->user){
                        return Html::a($user->username, ['/usermanager/user/view', 'id'=>$user->id]);
                    }
                }
            ],
            'order',

            [
                'class' => 'yii\grid\ActionColumn',
                'options'=>[
                    'style'=>'width:75px;'
                ]
            ],
        ],
    ]); ?>
</div>
