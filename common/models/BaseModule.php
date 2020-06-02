<?php

namespace reketaka\helps\common\models;

use reketaka\helps\common\helpers\Bh;
use Yii;
use yii\base\Module;
use function array_pop;
use function explode;
use function implode;

class BaseModule extends Module{

    public function init(){
        parent::init();

        $namespaceArray = explode("\\", static::class);
        array_pop($namespaceArray);
        $namespaceArray = implode("\\", $namespaceArray);

        if($this->isFrontend()){
            $this->viewPath = $this->getBasePath()."/viewsFrontend/";
            $this->controllerNamespace = $namespaceArray.'\frontendControllers';
        }

        if($this->isBackend()){
            $this->viewPath = $this->getBasePath()."/viewsBackend/";
            $this->controllerNamespace = $namespaceArray."\backendControllers";
        }

        if($this->isConsole()){
            $this->controllerNamespace = $namespaceArray."\commands";

        }
    }

    public function isBackend(){
        return Yii::$app->id == 'app-backend';
    }

    public function isFrontend(){
        return Yii::$app->id == 'app-frontend';
    }

    public function isConsole(){
        return \Yii::$app instanceof \yii\console\Application;

    }
}