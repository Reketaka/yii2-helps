<?php

use reketaka\helps\modules\catalog\models\PriceType;
use yii\web\View;
use kartik\detail\DetailView;
use yii\helpers\Html;
use reketaka\helps\modules\catalog\Module;

/**
 * @var $this View
 * @var $model PriceType
 * @var $fields[]
 */

?>


<div>

    <div>
        <?=Html::a(Module::t('app', 'update'), ['update', 'id'=>$model->id], ['class'=>'btn btn-success'])?>
    </div>

    <?php

        $attributes = [
            'id',
            'title',
            'total_amount',
            'created_at',
            'updated_at'
        ];

        $attributes = array_merge($attributes, $fields);

    ?>

    <?=DetailView::widget([
        'model'=>$model,
        'condensed'=>true,
        'hover'=>true,
        'mode'=>DetailView::MODE_VIEW,
        'enableEditMode' => false,
        'panel'=>[
            'heading'=>$this->title,
            'type'=>DetailView::TYPE_INFO,
        ],
        'attributes'=>$attributes
    ])?>
</div>
