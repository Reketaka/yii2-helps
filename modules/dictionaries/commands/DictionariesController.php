<?php

namespace reketaka\helps\modules\dictionaries\commands;

use reketaka\helps\common\helpers\Bh;
use reketaka\helps\modules\dictionaries\models\DictionariesName;
use reketaka\helps\modules\dictionaries\models\DictionariesValue;
use yii\console\Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;

class DictionariesController extends Controller{

    /**
     * Возвращает все доступные справочники системы
     */
    public function actionGetAll(){

        if(!$items = DictionariesName::find()->all()){
            echo "Справочников не найдено".PHP_EOL;
            return;
        }

        echo "List Dictionaries:".PHP_EOL;

        $maxLength = Bh::getMaxLengthOfWords($items, function($d){
            return $d->title." (".$d->alias.")";
        });


        $ms = Bh::appendWordToMaxLength("Title", $maxLength);


        echo $ms."   Amount Values".PHP_EOL;

        foreach($items as $item){
            $name = $item->title." (".$item->alias.")";
            $amountValues = $item->getDictionariesValues()->count();

            $name = Bh::appendWordToMaxLength($name, $maxLength);

            echo Console::ansiFormat($name, [Console::FG_YELLOW]).' = '.Console::ansiFormat($amountValues, [Console::FG_GREEN]).PHP_EOL;
        }

    }

    public function actionGet($alias){
        if(!$item = DictionariesName::findOne(['alias'=>$alias])){
            echo "Справочник не найден".PHP_EOL;
            return;
        }

        $dictionaryName = $item->title." (".$item->alias.")";
        echo "Список значений справочника ".Console::ansiFormat($dictionaryName, [Console::FG_YELLOW]).PHP_EOL;

        if(!$values = $item->dictionariesValues){
            echo "Справочник пустой".PHP_EOL;
            return;
        }

        $maxLength = Bh::getMaxLengthOfWords([$item->title." (".$item->alias.")"]);

        foreach($values as $value){

            $n = Bh::appendWordToMaxLength($dictionaryName, $maxLength);

            echo Console::ansiFormat($n, [Console::FG_YELLOW]) .' = '.Console::ansiFormat($value->value, [Console::FG_GREEN]).PHP_EOL;

        }

        return true;
    }

    public function actionAddValue($dAlias, $value){
        if(!$dictionary = DictionariesName::findOne(['alias'=>$dAlias])){
            echo "Справочник не найден".PHP_EOL;
            return;
        }

        if($dValue = $dictionary->getDictionariesValues()->where(['value'=>$value])->one()){
            echo "Такое значение уже есть в справочнике".PHP_EOL;
            return;
        }

        $dValue = new DictionariesValue([
            'dictionary_id' => $dictionary->id,
            'value' => $value
        ]);
        $dValue->save();


        echo "Значение ".Console::ansiFormat($value, [Console::FG_YELLOW])." успешно добавлено".PHP_EOL;
        return true;
    }
}