<?php

namespace reketaka\helps\modules\dictionaries\behavior;


use common\helpers\BaseHelper;
use reketaka\helps\modules\dictionaries\models\DictionariesHelper;
use yii\base\Behavior;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

class DictionaryBehavior extends Behavior{



    public $settings;

    public function getDictionaryValue($field){
        if(!array_key_exists($field, $this->settings)){
            throw new Exception("Can't find in settings field '$field'");
        }

        if(!$this->owner->$field){
            return false;
        }

        $value = DictionariesHelper::findInValues($this->settings[$field], $this->owner->$field, 'id', 'value');

        return $value;
    }

    public function getDictionaryData($field){
        if(!array_key_exists($field, $this->settings)){
            throw new Exception("Can't find in settings field '$field'");
        }

        if(!$this->owner->$field){
            return false;
        }

        $value = DictionariesHelper::findInValues($this->settings[$field], $this->owner->$field, 'id', 'full');

        return $value;
    }

    public function getDictionaryRelation($field){
        return DictionariesHelper::getRelationWith($this->owner, $this->settings[$field], $field);
    }
}