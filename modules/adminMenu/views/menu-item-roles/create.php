<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\menu\MenuItemRoles */
/**
 * @var $menuItems[]
 */

$this->title = Yii::t('app', 'Create Menu Item Roles');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Menu Item Roles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-item-roles-create">

    <?= $this->render('_form', [
        'model' => $model,
        'menuItems'=>$menuItems
    ]) ?>

</div>
