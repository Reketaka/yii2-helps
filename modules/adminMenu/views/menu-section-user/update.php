<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\menu\MenuSectionUser */

$this->title = Yii::t('app', 'Update Menu Section User: {nameAttribute}', [
    'nameAttribute' => $model->title,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Menu Section Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="menu-section-user-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
