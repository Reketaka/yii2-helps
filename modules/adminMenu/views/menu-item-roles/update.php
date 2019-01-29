<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\menu\MenuItemRoles */
/**
 * @var $menuItems[]
 */


$this->title = Yii::t('app', 'Update Menu Item Roles: {nameAttribute}', [
    'nameAttribute' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Menu Item Roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="menu-item-roles-update">

    <?= $this->render('_form', [
        'model' => $model,
        'menuItems'=>$menuItems
    ]) ?>

</div>
