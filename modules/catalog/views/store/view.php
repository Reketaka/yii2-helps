<?php

use reketaka\helps\modules\catalog\models\PriceType;
use yii\web\View;
use kartik\detail\DetailView;

/**
 * @var $this View
 * @var $model PriceType
 */

?>


<div>
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
        'attributes'=>[
            'id',
            'title',
            'alias',
            'uid',
            'comment',
            'created_at',
            'updated_at'
        ]
    ])?>
</div>
