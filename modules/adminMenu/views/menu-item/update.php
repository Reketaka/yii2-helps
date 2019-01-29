<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\menu\MenuItem */
/**
 * @var $sections[]
 */

$this->title = Yii::t('app', 'Update Menu Item: {nameAttribute}', [
    'nameAttribute' => $model->title,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Menu Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="menu-item-update">

    <?= $this->render('_form', [
        'model' => $model,
        'sections'=>$sections
    ]) ?>

</div>
