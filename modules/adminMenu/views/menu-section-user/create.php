<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\menu\MenuSectionUser */

$this->title = Yii::t('app', 'Create Menu Section User');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Menu Section Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-section-user-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
