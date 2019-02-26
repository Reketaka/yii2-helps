<?php

namespace reketaka\helps\common\models;

use yii\base\Model;

class FindAllControllers extends Model{

    public static function getControllersAndMethods(){

        $result = [];
//        $result += self::getControllersAndMethodsByPath(Yii::$app->controllerPath);

        foreach(Yii::$app->getModules(true) as $module){
            /**
             * @var $module Module
             */
            $baseNameSpace = get_class($module);
            $baseNameSpace = explode('\\', $baseNameSpace);
            array_pop($baseNameSpace);

            $baseNameSpace = implode('\\', $baseNameSpace);

            $controllerPath = $module->getControllerPath();
            $controllerPath = explode('/', $controllerPath);
            $controllerPath = array_pop($controllerPath);



            BaseHelper::dd($baseNameSpace);

            $result+= self::getControllersAndMethodsByPath($module->getControllerPath(), $baseNameSpace);
        }

        BaseHelper::dd($result);

        return;
    }

    /**
     * Возвращает Список всех контроллеров и методов системы
     */
    public static function getControllersAndMethodsByPath($path, $baseNameSpace = false){

        $files = FileHelper::findFiles($path, [
            'except'=>['.php']
        ]);



        $result = [];

        foreach($files as $file){
//        BaseHelper::dump($file);
//        BaseHelper::dump(Yii::getAlias('@root'));

            $fileNameSpace = str_replace([Yii::getAlias('@root')], [''], $file);
            $fileNameSpace = str_replace('/', "\\", $fileNameSpace);

            if($baseNameSpace){
                $fileNameSpace = basename($file);
                $fileNameSpace = '\\'.$baseNameSpace.'\\'.$fileNameSpace;
            }

            $fileNameSpace = str_replace('.php', '', $fileNameSpace);

            $result[$fileNameSpace] = [];

            $r = new \ReflectionClass($fileNameSpace);

            $methods = $r->getMethods(\ReflectionMethod::IS_PUBLIC);

            foreach($methods as $method){

                if($method->getDeclaringClass() != $r){
                    continue;
                }

                if(strpos($method->name, 'action') === FALSE){
                    continue;
                }

                $result[$fileNameSpace][] = $method->name;
            }

        }

        self::dd($result);

    }

}