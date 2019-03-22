<?php

namespace reketaka\helps\common\helpers;

use yii\helpers\VarDumper;
use Yii;

class Bh{

    /**
     * Возвращает случайный string
     * @param int $length
     * @return string
     */
    public static function generateRandomString($length = 10) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Возвращает распарсенный url
     * @param $url
     * @return array
     */
    public static function parseUrlQuery($url){

        $parse = parse_url($url);
        if(!isset($parse['query'])){
            return [];
        }

        $query = $parse['query'];
        $query = explode('&', $query);
        if(count($query) == 1 && $query[0] == $url){
            return [];
        }

        $queryAr = [];
        foreach($query as $q){
            $t = explode('=', $q);
            if(count($t) == 1 && $t[0] == $q){
                continue;
            }

            $queryAr[$t[0]] = $t[1];

        }

        return $queryAr;
    }

    /**
     * Возвращает строку в которой остаются только цифры
     * @param $var
     * @return integer
     */
    public static function onlyNumbers($var){
        return preg_replace("/[^0-9]/", '', $var);
    }

    /**
     * Возвращает строку в которой остаются только буквы
     */
    public static function onlyString($var){
        return preg_replace( "/[^a-zA-ZА-Яа-я\s]/ui", '', $var);
    }


    /**
     * Возвращает список полов с их индексами как они записываются в БД
     * 0 - женщина
     * 1 - мужчина
     * @return array
     */
    public static function getListSex(){
        return [
            Yii::t('app', 'sex_women'),
            Yii::t('app', 'sex_men')
        ];
    }

    /**
     * Вывод функции VarDumper::dump
     * @param $v
     */
    public static function dump($v){
        if(php_sapi_name() == 'cli'){
            var_Dump($v);
        }else {
            VarDumper::dump($v, 10, true);
        }
    }

    /**
     * Возвращает в виде массива полученые headers запроса
     * @return array|bool
     */
    public static function getHeaders($headers){
        if($headers) $headers = explode("\r", $headers);


        $dataHeaders = array();
        foreach($headers as $i) {
            if (strpos($i, "HTTP") !== FALSE) {
                $dataHeaders['HTTP'] = trim(substr($i, strpos($i, " "), strlen($i)));
                $dataHeaders['HTTP'] = self::onlyNumbers($dataHeaders['HTTP']);
                continue;
            }

            $name = substr($i, 0, strpos($i, ": "));
            $val = substr($i, strpos($i, ": ") + 2, strlen($i));

            if(trim($name) == "Set-Cookie"){
                $val = substr($val, 0, strpos($val, ";"));
                if(empty($val)) continue;
            }

            if(isset($dataHeaders[trim($name)])){

                if(is_array($dataHeaders[trim($name)])) $dataHeaders[trim($name)][] = trim($val);
                else{

                    $dataHeaders[trim($name)] = array($dataHeaders[trim($name)]);
                    $dataHeaders[trim($name)][] = trim($val);
                }

            }else $dataHeaders[trim($name)] = trim($val);


        }

        return $dataHeaders;
    }

    public static function getMemoryUsage(){
        $size = memory_get_usage(true);
        $unit=array('b','kb','mb','gb','tb','pb');
        return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
    }

    public static function dd($v){
        self::dump($v);
        exit();
    }

    public static function oneStringAndNumbers($var){
        return preg_replace('/[^a-zA-Zа-яА-Я0-9]/ui', '',$var );
    }

    /**
     * Выполняет подсчет по моделям по указанному полю
     * @param $provider
     * @param $fieldName
     * @return int
     */
    public static function getTotal($provider, $fieldName, $callback = null){
        $total = 0;

        if(!$callback) {
            foreach ($provider as $item) {
                $total += $item[$fieldName];
            }
        }

        if($callback instanceof \Closure){
            foreach($provider as $item){
                $total += $callback($item, $fieldName);
            }
        }

        return $total;
    }
}