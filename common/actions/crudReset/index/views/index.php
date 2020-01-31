<?php

use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\web\View;
use yii\bootstrap4\Html;
use yii\grid\GridView;

/**
 * @var $this View
 * @var $searchModel ActiveRecord
 * @var $dataProvider ActiveDataProvider
 * @var $columns[]
 * @var $addActions
 */

?>


<div class="index-box">

    <?php if($addActions):?>
        <div class="index-box-actions pb-3">

            <?=Html::a(Yii::t('app', 'create'), ['create'], ['class'=>'btn btn-success'])?>

        </div>
    <?php endif; ?>

    <div class="index-box-content">
        <?=GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => $columns
        ])?>
    </div>

</div>



