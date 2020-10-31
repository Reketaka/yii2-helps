<?php

namespace reketaka\helps\common\widgets\selectColumn;


use kartik\select2\Select2;
use reketaka\helps\common\assets\BootstrapNotificationAsset;
use yii\grid\Column;
use yii\helpers\Html;
use Yii;
use yii\helpers\Url;

class SelectColumn extends Column
{

    /**
     * {@inheritdoc}
     */
    public $header;

    public $format = 'html';

    public $attributeName = 'enable';

    public $data = [];

    public $enableSorting = true;

    public $headerOptions = ['style' => 'width:100px;'];

    public $placeholder = false;

    /**
     * Url адрес для переключения активности
     * @var bool
     */
    public $attributeSetUrl = false;

    public function init()
    {
        BootstrapNotificationAsset::register(Yii::$app->getView());

        parent::init();
    }

    protected function renderFilterCellContent()
    {
        $model = $this->grid->filterModel;

        $content = Select2::widget([
            'model' => $model,
            'attribute' => $this->attributeName,
            //            'name' => Html::getInputName($model, $this->enableAttributeName),
            'data' => $this->data,
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
        if($this->attributeSetUrl){
            $this->attributeSetUrl = str_replace(urlencode('{id}'), $key, $this->attributeSetUrl);
        }


        if(!$setAttributeUrl = $this->attributeSetUrl){
            $setAttributeUrl = Url::toRoute([\Yii::$app->controller->id.'/set-attribute', 'id'=> (string) $key, 'attributeName'=>$this->attributeName, 'value'=>'']);
        }

        $searchModel = $this->grid->filterModel;

        $dataSet = [
            'name' => __CLASS__,
            'attribute' => $this->attributeName,
            //            'name' => Html::getInputName($model, $this->enableAttributeName),
            'data' => $this->data,
            'options' => [
                'placeholder' => method_exists($model, 'getAttributeLabel') && $this->placeholder === FALSE?$model->getAttributeLabel($this->attributeName):''
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
            'value'=>$model->{$this->attributeName},
            'pluginEvents' => [
                "select2:select"=>"function(e){

                    $.getJSON('{$setAttributeUrl}'+e.params.data.id, function(response){
                        if(response.success){
                            flashMessage('Атрибут успешно изменен');
                            $(e.currentTarget).find(\"option[value='\"+e.params.data.id+\"']\").attr('selected', true)
                        }
                    });
                },",
                "select2:unselect"=>"function(e){

                    $.getJSON('{$setAttributeUrl}', function(response){
                        if(response.success){
                            flashMessage('Атрибут успешно изменен');
                            $('#".Html::getInputId($searchModel, $this->attributeName)."').html('');
                        }
                    });
                },",
            ]
        ];

        $content = Select2::widget($dataSet);
        return $content;
    }


}