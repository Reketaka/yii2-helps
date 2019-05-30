<?php

namespace reketaka\helps\modules\dictionaries\models;

use common\helpers\BaseHelper;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

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
     * Производит поиск в значениях справочника указанное значение по указанному полю $whereSearch
     * возвращает массив искомого значения ['id'=>'', 'value'=>'', 'alias'=>'']
     * @param $alias
     * @param $whereSearch
     * @return boolean|array
     */
    public static function findInValues($alias, $value, $whereSearch = 'alias'){

        if(!$values = self::getValues($alias, 'full')){
            return false;
        }

        foreach($values as $valueData){
            if($valueData[$whereSearch] == $value){
                return $valueData;
            }
        }

        return false;
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
            ->innerJoin(['dn'=>DictionariesName::tableName()], ['dictionaries_value.dictionary_id'=>new Expression("dn.id")])
            ->andOnCondition(['dn.alias'=>$dictionaryAlias]);
    }

}