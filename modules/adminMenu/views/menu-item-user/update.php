<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\menu\MenuItemUser */

$this->title = Yii::t('app', 'Update Menu Item User: {nameAttribute}', [
    'nameAttribute' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Menu Item Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="menu-item-user-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
