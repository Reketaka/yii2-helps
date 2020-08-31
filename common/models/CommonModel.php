<?php

namespace reketaka\helps\common\models;

use Yii;
use yii\base\Model;
use yii\log\Logger;
use function implode;

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

}