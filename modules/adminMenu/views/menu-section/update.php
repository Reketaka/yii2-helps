<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\menu\MenuSection */

$this->title = Yii::t('app', 'Update Menu Section: {nameAttribute}', [
    'nameAttribute' => $model->title,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Menu Sections'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="menu-section-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
