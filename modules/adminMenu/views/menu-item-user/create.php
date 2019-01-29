<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\menu\MenuItemUser */

$this->title = Yii::t('app', 'Create Menu Item User');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Menu Item Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-item-user-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
