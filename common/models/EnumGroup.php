<?php

namespace reketaka\helps\common\models;

abstract class EnumGroup{

    abstract public static function getItems():array;

    abstract public static function getTitles():array;

    /**
     * Возвращает title константы
     * @param $constName
     * @return string|null;
     */
    public static function getConstTitle($constName):?string{
        return static::getTitles()[$constName]??null;
    }

    public static function getArrayMap():array{
        return static::getTitles();
    }

}