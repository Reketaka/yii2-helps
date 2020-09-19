<?php

use reketaka\helps\common\helpers\Bh;
use reketaka\helps\modules\catalog\models\PriceType;
use yii\web\View;
use kartik\detail\DetailView;
use yii\helpers\Html;
use reketaka\helps\modules\catalog\Module;
use reketaka\helps\modules\catalog\models\ItemPrice;
use reketaka\helps\modules\catalog\models\ItemStore;

/**
 * @var $this View
 * @var $model PriceType
 * @var $fields[]
 * @var $itemPrices ItemPrice[]
 * @var $itemStores ItemStore[]
 */


?>


<div>

    <div>
        <?=Html::a(Module::t('app', 'update'), ['update', 'id'=>$model->id], ['class'=>'btn btn-success'])?>
    </div>

    <p></p>

    <?php

        $attributes = [
            'id',
            'title',
            [
                'attribute'=>'catalog_id',
                'format'=>'raw',
                'value'=>function()use($model){
                    if($catalog = $model->catalog){
                        return Html::a($catalog->title, ['catalog/view', 'id'=>$catalog->id]);
                    }
                }
            ],
            Bh::getCommonModelBooleanView('active', $model->active),
            'total_amount',
            'uid',
        ];

        $attributes = array_merge($attributes, []);

        $attributes = array_merge($attributes, [
            'created_at',
            'updated_at'
        ])

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

    <div class="row mt-3">
        <?php if($itemPrices):?>
            <div class="col-md-6">
                <?php

                    $attributes = [];
                    foreach($itemPrices as $itemPrice){
                        $attributes[] = [
                            'label'=>$itemPrice->priceType->title,
                            'value'=>Yii::$app->formatter->asDecimal($itemPrice->price)
                        ];
                    }

                ?>

                <?=DetailView::widget([
                    'model'=>$model,
                    'condensed'=>true,
                    'hover'=>true,
                    'mode'=>DetailView::MODE_VIEW,
                    'enableEditMode' => false,
                    'panel'=>[
                        'heading'=>Module::t('app', 'item_prices'),
                        'type'=>DetailView::TYPE_INFO,
                    ],
                    'attributes'=>$attributes
                ])?>
            </div>
        <?php endif; ?>

        <?php if($itemStores):?>
            <div class="col-md-6">
                <?php

                $attributes = [];
                foreach($itemStores as $itemStore){
                    $attributes[] = [
                        'label'=>$itemStore->store->title,
                        'value'=>$itemStore->amount
                    ];
                }

                ?>

                <?=DetailView::widget([
                    'model'=>$model,
                    'condensed'=>true,
                    'hover'=>true,
                    'mode'=>DetailView::MODE_VIEW,
                    'enableEditMode' => false,
                    'panel'=>[
                        'heading'=>Module::t('app', 'item_stores'),
                        'type'=>DetailView::TYPE_INFO,
                    ],
                    'attributes'=>$attributes
                ])?>
            </div>
        <?php endif; ?>
    </div>
</div>
