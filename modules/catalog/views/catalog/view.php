<?php

use reketaka\helps\modules\catalog\models\Catalog;
use reketaka\helps\modules\catalog\models\PriceType;
use reketaka\helps\modules\catalog\Module;
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
                'title',
                'alias',
                'uid',
                [
                    'attribute' => 'parent_id',
                    'format'=>'raw',
                    'value'=>function()use($model){
                        if($model->parent_id == Catalog::ROOT_CATALOG_ID){
                            return Html::tag('label', Module::t('app', 'root_catalog'), ['class'=>'label label-default']);
                        }
                    }
                ],
                'created_at',
                'updated_at'
            ]
    ])?>
</div>
