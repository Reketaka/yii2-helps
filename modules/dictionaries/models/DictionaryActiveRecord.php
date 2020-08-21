<?php

namespace reketaka\helps\modules\dictionaries\models;


use reketaka\helps\common\helpers\Bh;
use reketaka\helps\modules\dictionaries\behavior\DictionaryBehavior;
use yii\base\Exception;
use yii\db\ActiveQuery;
use function array_key_exists;
use function array_keys;
use function array_shift;
use function array_values;
use function count;

class DictionaryActiveRecord extends ActiveQuery {

    private $behavior = null;

    /**
     * @return DictionaryBehavior|null
     */
    private function getModelBehavior(){
        if($this->behavior){
            return $this->behavior;
        }

        $model = (new $this->modelClass);
        $this->behavior = $model->getBehavior(DictionaryBehavior::BEHAVIOR_NAME);
        return $this->behavior;
    }

    private function createCondition($condition){
        if(!$behavior = $this->getModelBehavior()){
            throw new Exception("Not found dictionary behavior");
        }


        if(count($condition) == 1){
            $field = array_keys($condition);
            $field = array_shift($field);

            $value = array_values($condition);
            $value = array_shift($value);

            if(!array_key_exists($field, $behavior->settings)){
                throw new Exception("Not found field in settings dictionary behavior");
            }

            $dictionaryAlias = $behavior->settings[$field];

            return [$field=>DictionariesHelper::findInValues($dictionaryAlias, $value, 'alias', 'id')];
        }

        if(count($condition) == 3){

            $field = $condition[1];
            $value = $condition[2];

            if(!array_key_exists($field, $behavior->settings)){
                throw new Exception("Not found field in settings dictionary behavior");
            }

            $dictionaryAlias = $behavior->settings[$field];

            $condition[2] = DictionariesHelper::findInValues($dictionaryAlias, $value, 'alias', 'id');

            return $condition;
        }
    }

    public function whereDictionary($condition){

        if(!$behavior = $this->getModelBehavior()){
            throw new Exception("Not found dictionary behavior");
        }

        $this->where($this->createCondition($condition));
        return $this;
    }

    public function andWhereDictionary($condition){
        if ($this->where === null) {
            $this->where = $this->createCondition($condition);
            return $this;
        }

        $this->where = ['and', $this->where, $this->createCondition($condition)];
        return $this;
    }

    public function orWhereDictionary($condition, $params = [])
    {
        if ($this->where === null) {
            $this->where = $this->createCondition($condition);
        } else {
            $this->where = ['or', $this->where, $this->createCondition($condition)];
        }
        $this->addParams($params);
        return $this;
    }

}