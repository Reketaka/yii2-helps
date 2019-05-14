<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model reketaka\helps\modules\dictionaries\models\DictionariesValue */
/**
 * @var $dictionaries[]
 */

$this->title = 'Create Dictionaries Value';
$this->params['breadcrumbs'][] = ['label' => 'Dictionaries Values', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dictionaries-value-create">

    <?= $this->render('_form', [
        'model' => $model,
        'dictionaries'=>$dictionaries
    ]) ?>

</div>
