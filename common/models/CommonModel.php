<?php

namespace reketaka\helps\common\models;

use Yii;
use yii\base\Model;
use yii\log\Logger;
use function array_merge;
use function implode;
use function is_array;

class CommonModel extends Model{

    public function flashMessage($msg, $type = 'success'){
        Yii::$app->session->setFlash($type, $msg);
        return true;
    }

    /**
     * Если есть ошибки валидации записывает их в лог
     */
    public function ifErrorLog(){
        if($this->hasErrors()){
            \Yii::getLogger()->log($this->errors, Logger::LEVEL_TRACE);
        }
    }

    /**
     * Проверяет есть ли ошибки в валидации если есть вешает список всех ошибок в session flash error
     * @return bool
     */
    public function hasErrorsFlash(){

        if(!$this->hasErrors()){
            return false;
        }

        Yii::$app->session->setFlash('error', $this->getErrorsString('</br>'));
        return true;
    }

    public function getErrorsString($delemiter = ', '){

        $text = [];
        foreach($this->getErrors() as $attribute=>$errors){
            $text[] = implode($delemiter, $errors);
        }
        return implode($delemiter, $text);
    }

    /**
     * Добавляет правила
     * ```
     * intStringRequired
     * [[$attribute], 'required'],
     * [[$attribute], 'string', 'max'=>255],
     * [[$attribute], 'filter', 'filter'=>'trim'],
     * [[$attribute], 'reketaka\helps\common\validators\OnlyInteger'],
     *
     * boolean
     * [[$attribute], 'integer', 'min'=>0, 'max'=>1],
     * [[$attribute], 'default', 'value'=>0],
     * ```
     *
     * @param $rules
     * @param $attribute
     * @param $type
     * @return bool
     */
    public function addRule(&$rules, $attributes, $type){

        if(!is_array($attributes)){
            $attributes = [$attributes];
        }

        if($type == 'stringRequired') {
            foreach($attributes as $attribute) {
                $rules = array_merge($rules, [[[$attribute], 'required']]);
                $rules = array_merge($rules, [[[$attribute], 'string', 'max' => 255]]);
                $rules = array_merge($rules, [[[$attribute], 'filter', 'filter' => 'trim']]);
                $rules = array_merge($rules, [[[$attribute], 'filter', 'filter' => 'mb_strtolower']]);
            }
        }

        if($type == 'intStringRequired') {
            foreach($attributes as $attribute) {
                $rules = array_merge($rules, [[[$attribute], 'reketaka\helps\common\validators\OnlyInteger']]);
                $rules = array_merge($rules, [[[$attribute], 'required']]);
                $rules = array_merge($rules, [[[$attribute], 'string', 'max' => 255]]);
                $rules = array_merge($rules, [[[$attribute], 'filter', 'filter' => 'trim']]);
            }
        }

        if($type == 'intString') {
            foreach($attributes as $attribute) {
                $rules = array_merge($rules, [[[$attribute], 'string', 'max' => 255]]);
                $rules = array_merge($rules, [[[$attribute], 'filter', 'filter' => 'trim']]);
                $rules = array_merge($rules, [[[$attribute], 'reketaka\helps\common\validators\OnlyInteger']]);
            }
        }

        if($type == 'text') {
            foreach($attributes as $attribute) {
                $rules = array_merge($rules, [[[$attribute], 'string']]);
                $rules = array_merge($rules, [[[$attribute], 'default', 'value' => null]]);
            }
        }

        if($type == 'boolean'){
            foreach($attributes as $attribute) {
                $rules = array_merge($rules, [[[$attribute], 'integer', 'min' => 0, 'max' => 1]]);
                $rules = array_merge($rules, [[[$attribute], 'default', 'value' => 0]]);
            }
        }

        if($type == 'dateTimestamp'){
            foreach($attributes as $attribute) {
                $rules = array_merge($rules,
                    [[[$attribute], 'datetime', 'format' => 'php:Y-m-d H:i', 'timestampAttribute' => $attribute]]);
            }
        }
        return true;
    }
}