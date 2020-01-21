<?php

use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\web\View;
use yii\grid\GridView;

/**
 * @var $this View
 * @var $searchModel ActiveRecord
 * @var $dataProvider ActiveDataProvider
 * @var $columns[]
 */

?>


<?=GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => $columns
])?>



