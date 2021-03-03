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
    public function getConstTitle($constName):?string{
        return $this->getTitles()[$constName]??null;
    }

    public function getArrayMap():array{
        return $this->getTitles();
    }

}