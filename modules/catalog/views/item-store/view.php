<?php

use reketaka\helps\modules\catalog\models\PriceType;
use yii\web\View;
use kartik\detail\DetailView;
use yii\bootstrap\Html;

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
                [
                    'attribute'=>'item_id',
                    'format'=>'raw',
                    'value'=>function()use($model){
                        if($item = $model->item){
                            return Html::a($item->title, ['item/view', 'id'=>$item->id]);
                        }
                    }
                ],
                [
                    'attribute'=>'store_id',
                    'format'=>'raw',
                    'value'=>function()use($model){
                        if($store = $model->store){
                            return Html::a($store->title, ['store/view', 'id'=>$store->id]);
                        }
                    }
                ],
                'amount',
                'created_at',
                'updated_at'
            ]
    ])?>
</div>
