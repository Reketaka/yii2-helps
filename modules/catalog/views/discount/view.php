<?php

use reketaka\helps\modules\catalog\models\Catalog;
use reketaka\helps\modules\catalog\models\PriceType;
use reketaka\helps\modules\catalog\Module;
use yii\web\View;
use kartik\detail\DetailView;
use yii\helpers\Html;
use reketaka\helps\common\helpers\Bh;

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
                Bh::getCommonModelBooleanView('active', $model->active),
                'value',
                'created_at',
                'updated_at'
            ]
    ])?>
</div>
