<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model reketaka\helps\modules\dictionaries\models\DictionariesName */

$this->title = 'Update Dictionaries Name: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Dictionaries Names', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dictionaries-name-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
