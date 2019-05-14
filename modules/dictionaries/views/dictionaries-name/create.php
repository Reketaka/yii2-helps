<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model reketaka\helps\modules\dictionaries\models\DictionariesName */

$this->title = 'Create Dictionaries Name';
$this->params['breadcrumbs'][] = ['label' => 'Dictionaries Names', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dictionaries-name-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
