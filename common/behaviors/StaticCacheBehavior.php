<?php

namespace reketaka\helps\common\behaviors;

use common\helpers\Bh;
use Yii;
use yii\base\Behavior;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\Response;
use function array_intersect;
use function array_key_exists;
use function array_keys;
use function array_uintersect;
use function file_put_contents;
use function http_build_query;
use function http_build_str;
use function http_build_url;
use function in_array;
use function is_array;
use function ob_get_clean;
use function str_replace;

class StaticCacheBehavior extends Behavior{

    CONST NAME = 'static-cache-page';

    CONST DEFAULT_PAGE = 'index.html';
    CONST PAGE_WITH_QUERY = 'index?{query}.html';

    /**
     * Можно указать массивом название actions для которых нужно включить статический кеш
     * если указать * будет сохранять все
     * @var string
     */
    public $actions = [
        'index'=>[
            'params'=>['id', 'sort', 'enable']
        ],
        'view'=>[
            'params'=>['id']
        ]
    ];

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

    public function getQueryString(){
        $queryParams = Yii::$app->request->getQueryParams();

        /**
         * Параметры которые сошлись
         */
        if(!$paramsIntersect = array_intersect(array_keys($queryParams), $this->actions['view']['params'])){
            return '';
        }

        $result = [];
        foreach($paramsIntersect as $paramName){
            $result[$paramName] = $queryParams[$paramName]??'';
        }

        return http_build_query($result);
    }

    public function saveCache(){
        $response = Yii::$app->getResponse();
        $response->off(Response::EVENT_AFTER_SEND, [$this, 'saveCache']);


        $cacheData = $response->content;

        $methodId = Yii::$app->controller->action->uniqueId;
        $queryString = $this->getQueryString();

        $cacheDirPath = Yii::getAlias($this->cachePath."/$methodId");
        $cacheFileName = self::DEFAULT_PAGE;


        if($queryString){
            $cacheFileName = str_replace("{query}", $queryString, self::PAGE_WITH_QUERY);
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
            array_key_exists($actionId, $this->actions)
        ){
            return true;
        }

        return false;
    }


}