<?php

namespace reketaka\helps\common\helpers;

use function array_key_exists;
use function array_keys;
use function array_merge;
use function array_search;
use function array_slice;
use function array_values;
use function implode;
use function mb_strlen;
use reketaka\helps\common\jobs\SendTelegramTextJob;
use function strlen;
use function strrpos;
use function substr;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use Yii;

class Bh{

    public static function appendWordToMaxLength($ms, $maxLength){
        if(mb_strlen($ms) < $maxLength){
            for($a = 0;($maxLength - mb_strlen($ms));$a++){
                $ms .= ' ';
            }
        }

        return $ms;
    }
    /**
     * Возвращает длину самого длинного слова
     * @param $items
     */
    public static function getMaxLengthOfWords($items, $callback = false){

        $maxLength = 0;
        foreach($items as $item){
            if(!($callback instanceof \Closure)){
                $mx = mb_strlen($item);
            }

            if($callback instanceof \Closure){
                $mx = mb_strlen($callback($item));
            }

            if($mx >= $maxLength){
                $maxLength = $mx;
            }
        }

        return $maxLength;
    }
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

    /**
     * Эмуляция функции ActiveRecord::deleteAll(['somthing'=>23])
     * Если нужно удалить все записи оставляйте $whereData = []
     * Только находит все модели и запускает удаление $model->delete();
     * если запускается из под консоли выводит информационные сообщения
     * @param $modelClass ActiveRecord
     * @param $whereData
     */
    public static function deleteAll($modelClass, $whereData = [], $outputConsole = false){
        $isConsole = \Yii::$app instanceof \yii\console\Application;


        if($whereData){
            $items = $modelClass::findAll($whereData);
        }

        if(!$whereData){
            $items = $modelClass::find()->all();
        }

        if(!$items){

            if($isConsole && $outputConsole) {
                echo "По модели " . Console::ansiFormat($modelClass, [Console::FG_YELLOW]) . " не найденно записей для удаления".PHP_EOL;
            }

            return false;
        }

        if($isConsole && $outputConsole) {
            echo "По модели " . Console::ansiFormat($modelClass, [Console::FG_GREEN]) . " найденно ".Console::ansiFormat(($count =count($items)), [Console::FG_GREEN])." записей для удаления".PHP_EOL;
        }

        foreach($items as $item){
            $item->delete();
        }

        if($isConsole && $outputConsole) {
            echo "По модели " . Console::ansiFormat($modelClass, [Console::FG_GREEN]) . " удаленно ".Console::ansiFormat($count, [Console::FG_GREEN])." записей".PHP_EOL;
        }


        return true;

    }

    public static function mb_ucfirst($string, $enc = 'UTF-8')
    {
        return mb_strtoupper(mb_substr($string, 0, 1, $enc), $enc) .
            mb_substr($string, 1, mb_strlen($string, $enc), $enc);
    }

    public static function getCommonModelBooleanView($attributeName, $value)
    {
        return [
            'attribute' => $attributeName,
            'format' => 'raw',
            'value' => function () use ($value) {
                if ($value) {
                    return Html::tag('span', null, ['class' => 'glyphicon glyphicon-ok']);
                }

                return Html::tag('span', null, ['class' => 'glyphicon glyphicon-remove']);
            }
        ];
    }

    public static function getCommonModelAsDecimial($attributeName, $value)
    {
        return [
            'attribute' => $attributeName,
            'format' => 'raw',
            'value' => function () use ($value) {
                return Yii::$app->formatter->asDecimal($value);
            }
        ];
    }

    /**
     * @param $attribute
     * @param $model ActiveRecord
     * @param $relName
     * @param $url
     * @param string $urlKey
     * @return array
     */
    public static function getCommonModelAsLinkRel($attribute, $model, $relName, $url, $linkKey = 'title', $urlKey = 'id'){

        $d = [
            'attribute' => $attribute,
            'format' => 'raw',
            'value' => function () use ($model, $relName, $linkKey, $urlKey, $url) {
                if($model->getRelation($relName) && ($r = $model->$relName)){
                    $url[$urlKey] = $r->$urlKey;
                    return Html::a($r->$linkKey, Url::to($url));
                }
            }
        ];

        return $d;
    }

    public static function getMaxValueColumn($array, $column, $toMax=false, $returnElem = false){

        if(!$toMax) {
            $columnData = ArrayHelper::getColumn($array, $column);
            return max($columnData);
        }

        $resultMax = 0;
        $resultKey = null;
        foreach($array as $key=>$arrayData){
            $arrayValue = is_object($arrayData)?$arrayData->{$column}:$arrayData[$column];

            if(($toMax >= $arrayValue) && ($resultMax <= $arrayValue)){
                $resultMax = $arrayValue;
                $resultKey = $key;
            }

        }

        if($returnElem){
            return [$resultKey=>$array[$resultKey]];
        }



        return $resultMax;
    }

    public static function sendMessageToTelegramJob($message, $type = 'HTML'){

        Yii::$app->queue->push(new SendTelegramTextJob([
            'message' => $message,
            'type'=>$type
        ]));
    }

    public static function sendMessageToTelegramAdmin($message, $type = 'HTML'){
        $bot = Yii::$app->bot;
        $chatId = \Yii::$app->params['telegramChatId'];

        try {
            $bot->sendMessage($chatId, $message, $type);
        }catch(\Exception $exception){
            Yii::error($exception->getMessage(), __METHOD__);
        }
    }

    public static function generateSchema($d){
        $text = Html::beginTag('script', ['type'=>'application/ld+json']).PHP_EOL;
        $text .= json_encode($d, JSON_UNESCAPED_SLASHES+JSON_UNESCAPED_UNICODE+JSON_PRETTY_PRINT).PHP_EOL;
        $text .= Html::endTag('script');
        return $text;
    }

    /**
     * Удаляет временную таблицу если таковая существует
     * @param $tableName
     * @throws \yii\db\Exception
     */
    public static function dropTemporaryTable($tableName){
        Yii::$app->db->createCommand("DROP TEMPORARY TABLE IF EXISTS $tableName;")->execute();
    }

    /**
     * @param $tableName
     * @param Query $query
     * @throws \yii\db\Exception
     */
    public static function selectQueryIntoTemporary($tableName, $query)
    {
        self::selectRawIntoTemporary($tableName, $query->createCommand()->getRawSql());
    }

    /**
     * @param $tableName
     * @param string $rawSqlQuery
     * @throws \yii\db\Exception
     */
    public static function selectRawIntoTemporary($tableName, $rawSqlQuery)
    {
        $q = (new Query())->createCommand()->setRawSql('CREATE TEMPORARY TABLE ' . $tableName . ' '
            . $rawSqlQuery);
        $q->execute();
    }

    /**
     * Удаляет файл если его размер больше указанного в мегабайтах
     * @param $filePath
     * @param $size
     * @return bool
     */
    public static function deleteFileIfSizeMore($filePath, $size){
        $filePath = Yii::getAlias($filePath);

        if(!file_exists($filePath)){
            return false;
        }


        $filesize = filesize($filePath);
        $sizeMb = $filesize/pow(1024, 2);

        if($sizeMb > $size){
            @unlink($filePath);
        }

        return true;
    }

    public static function formatMobileTelephone($telephone, $country = 'ru'){
        if(($country == 'ru') && (mb_strlen($telephone) != 11)){
            return $telephone;
        }

        if(($country == 'ru') && (mb_strlen($telephone) == 11)){
            return substr($telephone, 0, 1)." (".substr($telephone, 1, 3).") ".substr($telephone, 4, 3)."-".substr($telephone, 7, 2)."-".substr($telephone, 9, 2);
        }
    }

    public static function formatSecretTelephone($telephone){

        $text = [];
        $text[] = substr($telephone, 0, 2);
        for($a=0;$a!=strlen($telephone)-4;$a++){
            $text[] = '*';
        }
        $text[] = substr($telephone, -2);

        return implode("", $text);
    }

    public static function formatSecretEmail($email){

        $text = [];
        $text[] = substr($email, 0,1);
        for($a=0;$a!=strrpos($email, '@')-1;$a++){
            $text[] = '*';
        }
        $text[] = substr($email, strrpos($email, '@'), 1);
        $text[] = substr($email, strrpos($email, '@')+1, strlen($email));

        return implode("", $text);
    }

    public static function formatSecretAll($var){
        $words = explode(" ", $var);
        $text = [];
        foreach($words as $word){
            for($a=0;$a!=strlen($word);$a++){
                $text[] = "*";
            }
            $text[] = " ";
        }

        return implode("", $text);
    }

    public static function insertArrayAfter(&$array, $index, $key, $val)
    {
        if(!$searchKey = array_key_exists($index, $array)){
            return false;
        }

        $searchNumber = array_search($index, array_keys($array));

        $partStart = array_slice($array, 0, $searchNumber+1);

        $partEnd = array_slice($array, $searchNumber+1, count($array));

        $array = array_merge($partStart, [$key=>$val], $partEnd);
        return $array;
    }
    
    public static function wph_cut_by_words($maxlen, $text) {
        $len = (mb_strlen($text) > $maxlen)? mb_strripos(mb_substr($text, 0, $maxlen), ' ') : $maxlen;
        $cutStr = mb_substr($text, 0, $len);
        $temp = (mb_strlen($text) > $maxlen)? $cutStr. '...' : $cutStr;
        return $temp;
    }
}