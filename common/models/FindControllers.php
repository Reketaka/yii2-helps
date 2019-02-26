<?php

namespace reketaka\helps\common\models;

use yii\base\Model;
use Yii;
use reketaka\helps\common\helpers\Bh as BaseHelper;
use yii\base\Module;
use yii\helpers\FileHelper;
use yii\web\Application;


//Event::on(View::class, View::EVENT_AFTER_RENDER, function($event){
//
//    $fc = (new FindControllers);
//
//    if($fc->hasCurrentActiveMethod()){
//
//
////        BaseHelper::dd($event->params['model']->id);
//
////        BaseHelper::dump($event->sender->view->title);
////        BaseHelper::dd(Yii::$app->view);
//        $title = "Тест сука";
//
//        if(array_key_exists('model', $event->params) && ($model = $event->params['model']) && ($model)){
//            $title = $model->id.' '.$title;
//        }
//
//        Yii::$app->view->title = $title;
//
//        Yii::$app->view->registerMetaTag([
//            'name'=>'description',
//            'content'=>'tes'
//        ]);
//    }
//
//});

class FindControllers extends Model{

    CONST CACHE_NAME = 'reketakaFindControllerGetAll';

    private $useCache = true;

    /**
     * Возвращает true если текущий контроллер и метод есть в списке
     * @return bool
     */
    public function hasCurrentActiveMethod(){
        list($controller, $action) = $this->getCurrentControllerMethod();

        $listOfAll = $this->getAll();

        if(!(array_key_exists($controller, $listOfAll)) && !(in_array($action, $listOfAll[$controller]))){
            return false;
        }

        return true;
    }

    /**
     * Возвращает список всех контроллеров и методов
     * @return array
     */
    public function getAll(){

        if($this->useCache && ($result = Yii::$app->cache->get(self::CACHE_NAME))){
            return $result;
        }

        $result = $this->getControllersAndMethods(Yii::$app);
        Yii::$app->cache->set(self::CACHE_NAME, $result, time());

        return $result;
    }

    private function getCurrentControllerMethod(){
        $action = Yii::$app->controller->action->id;
        $action = explode('-', $action);
        $action = array_map(function($v){
            return ucfirst($v);
        }, $action);
        $action = implode('', $action);
        $action = 'action'.$action;

        $controller = get_class(Yii::$app->controller);

        return [$controller, $action];
    }

    private function getControllerNameSpaceOfModule($module){

        $baseNameSpace = get_class($module);
        $baseNameSpace = explode('\\', $baseNameSpace);
        array_pop($baseNameSpace);

        $baseNameSpace = implode('\\', $baseNameSpace);

        $controllerPath = $module->getControllerPath();
        $controllerPath = explode('/', $controllerPath);
        $controllerPath = array_pop($controllerPath);
        $baseNameSpace = $baseNameSpace.'\\'.$controllerPath;

        return $baseNameSpace;
    }

    /**
     * @param $module Module
     */
    private function getControllersAndMethods($module){

        $result = [];

        /**
         * @var $module Module
         */
        $baseNameSpace = false;
        if(!($module instanceof Application)) {
            $baseNameSpace = $this->getControllerNameSpaceOfModule($module);
        }

        $result = array_merge($result, self::getControllersAndMethodsByPath($module->getControllerPath(), $baseNameSpace));

        foreach($module->getModules(true) as $subModule) {
            $result = array_merge($result, $this->getControllersAndMethods($subModule));
        }

        return $result;
    }

    private static function getControllersAndMethodsByPath($path, $baseNameSpace = false){

        $files = FileHelper::findFiles($path, [
            'except'=>['.php']
        ]);

        $result = [];

        foreach($files as $file){

            $fileNameSpace = str_replace([Yii::getAlias('@root')], [''], $file);
            $fileNameSpace = str_replace('/', "\\", $fileNameSpace);


            if($baseNameSpace){
                $fileNameSpace = basename($file);
                $fileNameSpace = $baseNameSpace.'\\'.$fileNameSpace;
            }

            if(!$baseNameSpace){
                $fileNameSpace = trim($fileNameSpace, '\\');
            }

            $fileNameSpace = str_replace('.php', '', $fileNameSpace);

            $fileNameSpaceToInsert = str_replace('\\'.$baseNameSpace.'\\', '', $fileNameSpace);

            $result[$fileNameSpace] = [];

            $r = new \ReflectionClass($fileNameSpace);

            $methods = $r->getMethods(\ReflectionMethod::IS_PUBLIC);

            foreach($methods as $method){

//                if($method->getDeclaringClass() != $r){
//                    continue;
//                }

                if(strpos($method->name, 'action') === FALSE || $method->name == 'actions'){
                    continue;
                }

                $result[$fileNameSpace][] = $method->name;
            }

        }

        return $result;

    }

}