<?php

use reketaka\helps\modules\catalog\models\PriceType;
use yii\web\View;
use kartik\detail\DetailView;
use yii\helpers\Html;

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
                    'attribute'=>'price_type_id',
                    'format'=>'raw',
                    'value'=>function()use($model){
                        if($priceType = $model->priceType){
                            return Html::a($priceType->title, ['price-type/view', 'id'=>$priceType->id]);
                        }
                    }
                ],
                [
                    'attribute'=>'price',
                    'format'=>'raw',
                    'value'=>function()use($model){
                        return Yii::$app->formatter->asDecimal($model->price);
                    }
                ],
                'created_at',
                'updated_at'
            ]
    ])?>
</div>
