<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\menu\MenuSection */

$this->title = Yii::t('app', 'Create Menu Section');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Menu Sections'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-section-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
