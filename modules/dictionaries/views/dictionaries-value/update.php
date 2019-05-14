<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model reketaka\helps\modules\dictionaries\models\DictionariesValue */
/**
 * @var $dictionaries[]
 */

$this->title = 'Update Dictionaries Value: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Dictionaries Values', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dictionaries-value-update">

    <?= $this->render('_form', [
        'model' => $model,
        'dictionaries'=>$dictionaries
    ]) ?>

</div>
