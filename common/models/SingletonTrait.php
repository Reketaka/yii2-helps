<?php

namespace reketaka\helps\common\models;


use Yii;

trait SingletonTrait{

    public static function getInstance(){

        $container = Yii::$container;

        if($container->hasSingleton(self::class)){
            return $container->get(self::class);
        }


        $container->setSingleton(self::class);

        return $container->get(self::class);
    }

}