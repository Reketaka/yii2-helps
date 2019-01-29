<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\menu\MenuItem */
/**
 * @var $sections[]
 */

$this->title = Yii::t('app', 'Create Menu Item');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Menu Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-item-create">

    <?= $this->render('_form', [
        'model' => $model,
        'sections'=>$sections
    ]) ?>

</div>
