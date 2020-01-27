<?php

namespace reketaka\helps\common\models;

class DynamicModel extends \yii\base\DynamicModel{

    protected $_labels;

    public function setAttributeLabel($attribute, $label){
        $this->_labels[$attribute] = $label;
    }

    public function setAttributeLabels($labels){
        $this->_labels = $labels;
    }

    public function getAttributeLabel($name){
        return $this->_labels[$name] ?? $name;
    }

}