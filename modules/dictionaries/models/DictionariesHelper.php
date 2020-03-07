<?php

namespace reketaka\helps\modules\dictionaries\models;

use reketaka\helps\common\helpers\Bh;
use yii\base\Exception;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;

class DictionariesHelper extends Model{

    /**
     * Возвращает ключ кеша для значений определенного справочника
     * @param $alias
     * @return string
     */
    public static function getCacheName($alias){
        $cacheName = [];
        $cacheName[] = 'DictionaryValues';
        $cacheName[] = $alias;
        return md5(implode(' ', $cacheName));
    }

    /**
     * Очистить кеш значений определенного справчоника
     * @param $alias
     * @return bool
     */
    public static function clearCache($alias){
        \Yii::$app->cache->delete(self::getCacheName($alias));
        return true;
    }

    /**
     * Возвращает модель DictionaryValuesValue для значения поля определенного справочника
     * @param $dictionaryAlias
     * @param $valueAlias
     * @return array|bool|DictionariesName|ActiveRecord|null
     */
    public static function getValue($dictionaryAlias, $valueAlias){

        $cache = \Yii::$app->cache;
        $key = ['DictionaryValueOne', $dictionaryAlias, $valueAlias];
        $key = md5(implode(' ', $key));

        if($cacheData = $cache->get($key)){
            return $cacheData;
        }

        if(!$dictionary = DictionariesName::findOne(['alias'=>$dictionaryAlias])){
            return false;
        }

        if(!$value = $dictionary->getDictionariesValues()->where(['dictionaries_value.alias'=>$valueAlias])->one()){
            return false;
        }

        $cache->set($key, $value);

        return $value;
    }

    /**
     * Возвращает id Значения справочника
     * @param $dictionaryAlias
     * @param $valueAlias
     * @return array|bool
     */
    public static function getId($dictionaryAlias, $valueAlias){
        return self::findInValues($dictionaryAlias, $valueAlias, 'alias', 'id');
    }

    /**
     * Производит поиск в значениях справочника указанное значение по указанному полю $whereSearch
     * возвращает массив искомого значения ['id'=>'', 'value'=>'', 'alias'=>'']
     * @param $alias
     * @param $whereSearch
     * @return boolean|array
     */
    public static function findInValues($alias, $value, $whereSearch = 'alias', $fieldReturn = 'full'){
        if(!in_array($fieldReturn, ['full', 'id', 'alias', 'value'])){
            return [];
        }


        if(!$values = self::getValues($alias, 'full')){
            return [];
        }

        foreach($values as $valueData){
            if($valueData[$whereSearch] == $value){
                return $fieldReturn == 'full'?$valueData:$valueData[$fieldReturn];
            }
        }

        return [];
    }

    /**
     * Возвращает все значения справчоника в формате [$from=>'value']
     * если указать $from = 'full' ['id'=>['alias'=>'', 'value'=>'']]
     * @param $alias
     * @return array
     */
    public static function getValues($alias, $from='id'){
        if(!in_array($from, ['id', 'alias', 'full'])){
            return [];
        }

        $cache = \Yii::$app->cache;
        $cacheName = self::getCacheName($alias.$from);

        if($cacheData = $cache->get($cacheName)){
            return $cacheData;
        }

        if(!$dictionary = DictionariesName::findOne(['alias'=>$alias])){
            return [];
        }

        if(!$values = $dictionary->dictionariesValues){
            return [];
        }

        $result = [];
        if($from != 'full'){
            $result = ArrayHelper::map($values, $from, 'value');
        }

        if($from == 'full'){
            $result = ArrayHelper::toArray($values, [
                'reketaka\helps\modules\dictionaries\models\DictionariesValue'=>[
                    'id',
                    'value',
                    'alias'
                ]
            ]);
            $result = ArrayHelper::index($result, 'id');
        }


        $cache->set($cacheName, $result);


        return $result;
    }

    /**
     * Создает справочник с переданными значениями
     * @param $alias
     * @param array $dataValues
     * @return boolean
     */
    public static function create($alias, $values=[], $title=false, $valueWithAlias=false){
        if(!$values){
            return false;
        }

        $dictionary = new DictionariesName([
            'alias'=>$alias,
            'title'=>$title?$title:$alias
        ]);

        if(!$dictionary->validate()){
            return false;
        }

        $dictionary->save();

        if(!$values){
            return true;
        }

        foreach($values as $alias=>$value){
            $dictionaryValue = new DictionariesValue([
                'dictionary_id' => $dictionary->id,
                'value'=>$value
            ]);

            if($valueWithAlias){
                $dictionaryValue->alias = $alias;
            }

            $dictionaryValue->save();
        }


        return true;
    }

    /**
     * Возвращает связь со справочникам для реализации связи в моделя ActiveRecord hasOne hasMany
     */
    public static function getRelationWith(ActiveRecord $model, $dictionaryAlias, $fieldName){
        return $model->hasOne(DictionariesValue::class, ['id'=>$fieldName])
            ->innerJoin(['dn'=>DictionariesName::tableName()], "dictionaries_value.dictionary_id = dn.id AND dn.alias = '$dictionaryAlias'");
    }

    public static function convertToDictionary($oldTableNameWithStatus, $dictionaryName, $accordance, $dataTableChanges, $debug = true){


        $oldStatuses = (new Query())->select(array_values($accordance))->from($oldTableNameWithStatus)->createCommand()->queryAll();
        $oldStatusesAlias = ArrayHelper::map($oldStatuses, $accordance['alias'], $accordance['title']);

        if(!DictionariesName::findOne(['alias'=>$dictionaryName])){
            echo "Создаем новый справочник данных ".Console::ansiFormat($dictionaryName, [Console::FG_YELLOW]).PHP_EOL;
            if(!self::create($dictionaryName, $oldStatusesAlias, $dictionaryName, true)){
                throw new Exception("Не смогли создать справочник");
            }
        }


        foreach($oldStatuses as $key=>$oldStatusItem){
            if(!array_key_exists($oldStatusItem[$accordance['alias']], $oldStatusesAlias)){
                continue;
            }

            $newStatusId = self::findInValues($dictionaryName, $oldStatusItem[$accordance['alias']], 'alias', 'id');

            $oldStatuses[$key]['new_id'] = $newStatusId;
        }


        $transaction = \Yii::$app->db->beginTransaction();

//        BaseHelper::dump($oldStatuses);
        $newStatusDataKeyId = self::getValues($dictionaryName, 'id');
//        BaseHelper::dump($newStatusData);

        /**
         * Ищем foreignKey на указанные колонки
         */
        foreach($dataTableChanges as $tableName=>$columns) {
            foreach ($columns as $columnName) {

                $sql = "SELECT
    TABLE_NAME,COLUMN_NAME,CONSTRAINT_NAME, REFERENCED_TABLE_NAME,REFERENCED_COLUMN_NAME
FROM
    INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE
        REFERENCED_TABLE_NAME = '$oldTableNameWithStatus' AND
        TABLE_NAME = '$tableName' AND
        REFERENCED_COLUMN_NAME = 'id'";

                $foreignData = \Yii::$app->db->createCommand($sql)->queryOne();

                if(!$foreignData){
                    continue;
                }

                echo "Найдена установка foreignKey на колонку ".Console::ansiFormat($columnName, [Console::FG_YELLOW])." в таблице ".Console::ansiFormat($tableName, [Console::FG_YELLOW])." удаляем её".PHP_EOL;


                if(!$debug) {
                    \Yii::$app->db->createCommand()->dropForeignKey($foreignData['CONSTRAINT_NAME'],
                        $foreignData['TABLE_NAME'])->execute();
                }

            }
        }

        try {

            foreach($dataTableChanges as $tableName=>$columns){
                foreach($columns as $columnName){
                    echo "Начинаем менять статусы в таблице ".Console::ansiFormat($tableName, [Console::FG_GREEN])." в колонке ".Console::ansiFormat($columnName, [Console::FG_YELLOW]).PHP_EOL;

                    $oldStatusIds = ArrayHelper::getColumn($oldStatuses, 'id');
                    foreach($oldStatuses as $oldStatusData){
                        if(array_key_exists($oldStatusData['id'], $newStatusDataKeyId)){
                            echo Console::ansiFormat("Пропускаем этот статус потому что старый id совпадает с новым", [Console::FG_YELLOW]).PHP_EOL;
                            continue;
                        }

                        $sqlUpdate = \Yii::$app->db->createCommand()->update($tableName, [$columnName=>$oldStatusData['id']], "$columnName = {$oldStatusData['new_id']}");

                        echo $sqlUpdate->getRawSql().PHP_EOL;

                        if(!$debug){
                            $sqlUpdate->execute();
                        }

                    }

                }
            }

        }catch (\Exception $exception){
            $transaction->rollBack();
        }

        $transaction->commit();


        /**
         * Устанавливаем новые foreignKey
         */
        foreach($dataTableChanges as $tableName=>$columns) {
            foreach ($columns as $columnName) {

                $foreignKeyName = "fk-$tableName-$columnName-".DictionariesValue::tableName()."-id";

                echo "Устанавливаем foreignKey ".Console::ansiFormat($tableName, [Console::FG_YELLOW])." на колонку ".Console::ansiFormat($columnName, [Console::FG_YELLOW])." ссылаемся на таблицу ".Console::ansiFormat(DictionariesValue::tableName(), [Console::FG_YELLOW])." колонка ".Console::ansiFormat("id", [Console::FG_YELLOW]).PHP_EOL;

                if(!$debug) {
                    \Yii::$app->db->createCommand()->addForeignKey($foreignKeyName, $tableName, $columnName,
                        DictionariesValue::tableName(), 'id')->execute();
                }

            }
        }




//        BaseHelper::dd($oldStatuses);

    }

}