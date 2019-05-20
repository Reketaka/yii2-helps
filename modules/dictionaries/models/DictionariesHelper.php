<?php

namespace reketaka\helps\modules\dictionaries\models;

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
     * Возвращает все значения справчоника в формате ['id'=>'value']
     * @param $alias
     * @return array
     */
    public static function getValues($alias){

        $cache = \Yii::$app->cache;
        $cacheName = self::getCacheName($alias);

        if($cacheData = $cache->get($cacheName)){
            return $cacheData;
        }

        if(!$dictionary = DictionariesName::findOne(['alias'=>$alias])){
            return [];
        }

        if(!$values = $dictionary->dictionariesValues){
            return [];
        }

        $result = ArrayHelper::map($values, 'id', 'value');

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
    public static function getRelationWith(ActiveRecord $model, $dictionaryAlias){
        return $model->hasOne(DictionariesValue::class, ['id'=>'type_ur_help'])
            ->innerJoin(['dn'=>DictionariesName::tableName()], ['dictionaries_value.dictionary_id'=>new Expression("dn.id")])
            ->andOnCondition(['dn.alias'=>$dictionaryAlias]);
    }

}