<?php

namespace reketaka\helps\common\controllers;

use yii\base\Module;
use yii\console\Controller;

class ConsoleController extends Controller{

    public $fopen;

    public function __construct(string $id, Module $module, array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->fopen = $this->getFopen();
    }

    public function getFopen(){
        $path = str_replace('\\', '/', \Yii::getAlias('@root').'/'.self::className()).'.php';
        return fopen($path, 'r+');
    }

    public function checkLock(){

        if(!flock($this->fopen, LOCK_EX|LOCK_NB)){
            echo "Предыдущий процесс ещё не завершился".PHP_EOL;
            exit();
        }

    }

    public function lock(){
        flock($this->fopen, LOCK_EX);
    }

    public function unlock(){
        flock($this->fopen, LOCK_UN);
    }

}