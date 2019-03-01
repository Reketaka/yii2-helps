<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model reketaka\helps\modules\seo\models\Seo */
/**
 * @var $controllers[]
 */

$this->title = 'Create Seo';
$this->params['breadcrumbs'][] = ['label' => 'Seos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="seo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'controllers'=>$controllers
    ]) ?>

</div>
