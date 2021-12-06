<?php

namespace reketaka\helps\common\behaviors;

use common\helpers\Bh;
use Yii;
use yii\base\Behavior;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\Response;
use function file_put_contents;
use function in_array;
use function is_array;
use function ob_get_clean;

class StaticCacheBehavior extends Behavior{

    CONST NAME = 'static-cache-page';
    /**
     * Можно указать массивом название actions для которых нужно включить статический кеш
     * @var string
     */
    public $actions = "*";

    public $cachePath = "@console/runtime/staticCache";

    public function events()
    {
        return [
            Controller::EVENT_AFTER_ACTION=>'afterAction'
        ];
    }


    public function afterAction($event){
        if(!$this->canRun($event->action->id)){
            return null;
        }

        $response = Yii::$app->getResponse();

        $response->on(Response::EVENT_AFTER_SEND, [$this, 'saveCache']);

    }


    public function saveCache(){
        $response = Yii::$app->getResponse();
        $response->off(Response::EVENT_AFTER_SEND, [$this, 'saveCache']);


        $cacheData = $response->content;

        $methodId = Yii::$app->controller->action->uniqueId;
        $queryString = Yii::$app->request->getQueryString();

        $cacheDirPath = Yii::getAlias($this->cachePath."/$methodId");
        $cacheFileName = 'index.html';
        if($queryString){
            $cacheFileName = "index?$queryString.html";
        }

        FileHelper::createDirectory($cacheDirPath);

        $cacheFilePath = $cacheDirPath."/".$cacheFileName;

        file_put_contents($cacheFilePath, $cacheData);


    }

    /**
     * Метод возвращает разрешение на запуск замен
     * @param $actionId
     * @return bool
     */
    private function canRun($actionId){
        if($this->actions == "*"){
            return true;
        }

        if(
            is_array($this->actions) &&
            $this->actions &&
            in_array($actionId, $this->actions)
        ){
            return true;
        }

        return false;
    }


}