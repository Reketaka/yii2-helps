<?php

namespace reketaka\helps\common\widgets\inputColumn;


use kartik\select2\Select2;
use yii\grid\Column;
use yii\helpers\Html;
use Yii;
use yii\helpers\Url;
use yii\web\View;

class InputColumnWidget extends Column
{

    /**
     * {@inheritdoc}
     */
    public $header;

    public $format = 'raw';

    public $attributeName = 'enable';

    public $enableSorting = true;

    public $headerOptions = ['style' => 'width:100px;'];

    public $attributeToggle = false;

    public $assetClass;

    protected function renderFilterCellContent()
    {
        $model = $this->grid->filterModel;

        $content = Select2::widget([
            'model' => $model,
            'attribute' => $this->attributeName,
            //            'name' => Html::getInputName($model, $this->enableAttributeName),
            'data' => [
                '1' => Yii::t('app', 'yes'),
                '0' => Yii::t('app', 'no')
            ],
            'options' => [
                'placeholder' => method_exists($model, 'getAttributeLabel')?$model->getAttributeLabel($this->attributeName):''
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
        return $content;
    }

    protected function renderHeaderCellContent()
    {
        if ($this->header !== null) {
            return parent::renderHeaderCellContent();
        }

        $model = $this->grid->filterModel;

        $modelClass = $this->grid->dataProvider->query->modelClass;
        $this->header = $modelClass::instance()->getAttributeLabel($this->attributeName);

        if ($this->attributeName && $this->enableSorting && ($sort = $this->grid->dataProvider->getSort()) !== false && $sort->hasAttribute($this->attributeName)) {
            return $sort->link($this->attributeName);
        }

        return $this->header;
    }

    /**
     * {@inheritdoc}
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        if (!$model->hasAttribute($this->attributeName)) {
            return null;
        }


        $url = Url::to(['change-input', 'attribute'=>$this->attributeName, 'value'=>'']);

        $content = '<div class="input-group mb-3">';

        $content .= Html::input('text', '', $model->{$this->attributeName}, ['class'=>'form-control inputWidgetInputColumn', 'data-id'=>$model->id]);

        $content .= '
  <div class="input-group-append">
    <span class="input-group-text" id="basic-addon2">
        <button type="button" class="btn btn-default btn-sm saveWidgetInputColumn" data-id="'.$model->id.'"><i class="fa fa-check"></i></button>
    </span>
  </div>
</div>';

        if($this->assetClass) {
            Yii::$app->view->registerAssetBundle($this->assetClass, View::POS_END);
        }

        $jsText = <<<JS
    $('.inputWidgetInputColumn').keyup(function(e){
        if(e.keyCode != 13){
            return false;
        }
        e.preventDefault();
        
        var value = $(this).val();
        var id = $(this).data('id');
        
        $.getJSON('{$url}'+value+'&id='+id, function(response){
            
             $.toast({
                title:response.title,
                content:response.message,
                type: 'info',
                delay: 3000
            });
        });
        
        return false;
        
    });
        
    $(".saveWidgetInputColumn").click(function(e){
        e.preventDefault();
        
        var value = $(this).closest('.input-group').find('.inputWidgetInputColumn').val();
        var id = $(this).data('id');
        
        $.getJSON('{$url}'+value+'&id='+id, function(response){
            
             $.toast({
                title:response.title,
                content:response.message,
                type: 'info',
                delay: 3000
            });
        });
        
        return false;
    })
JS;

        Yii::$app->view->registerJs($jsText, View::POS_END, self::className());



        return $this->grid->formatter->format($content, $this->format);
    }


}