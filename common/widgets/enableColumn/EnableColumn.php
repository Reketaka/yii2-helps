<?php

namespace reketaka\helps\common\widgets\enableColumn;


use kartik\select2\Select2;
use yii\grid\Column;
use yii\helpers\Html;
use Yii;
use yii\helpers\Url;

class EnableColumn extends Column
{

    /**
     * {@inheritdoc}
     */
    public $header;

    public $format = 'html';

    public $enableAttributeName = 'enable';

    public $enableSorting = true;

    public $headerOptions = ['style' => 'width:100px;'];

    public $attributeToggle = false;
    /**
     * Url адрес для переключения активности
     * @var bool
     */
    public $attributeToggleUrl = false;

    protected function renderFilterCellContent()
    {
        $model = $this->grid->filterModel;

        $content = Select2::widget([
            'model' => $model,
            'attribute' => $this->enableAttributeName,
            //            'name' => Html::getInputName($model, $this->enableAttributeName),
            'data' => [
                '1' => Yii::t('app', 'yes'),
                '0' => Yii::t('app', 'no')
            ],
            'options' => [
                'placeholder' => method_exists($model, 'getAttributeLabel')?$model->getAttributeLabel($this->enableAttributeName):''
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
        $this->header = $modelClass::instance()->getAttributeLabel($this->enableAttributeName);

        if ($this->enableAttributeName && $this->enableSorting && ($sort = $this->grid->dataProvider->getSort()) !== false && $sort->hasAttribute($this->enableAttributeName)) {
            return $sort->link($this->enableAttributeName);
        }

        return $this->header;
    }

    /**
     * {@inheritdoc}
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        if (!$model->hasAttribute($this->enableAttributeName)) {
            return null;
        }

        $content = Html::tag('span', null, ['class' => 'glyphicon glyphicon-remove']);

        if ($model->{$this->enableAttributeName}) {
            $content = Html::tag('span', null, ['class' => 'glyphicon glyphicon-ok']);
        }

        if($this->attributeToggle){
            $url = '#';
            if(!$this->attributeToggleUrl){
                $url = Url::toRoute([\Yii::$app->controller->id.'/toggle-attribute', 'id'=> (string) $key, 'attributeName'=>$this->enableAttributeName]);
            }

            $content = Html::a($content, $url);
        }

        return $this->grid->formatter->format($content, $this->format);
    }


}