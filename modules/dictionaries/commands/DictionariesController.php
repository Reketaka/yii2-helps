<?php

namespace reketaka\helps\modules\dictionaries\commands;

use common\helpers\BaseHelper;
use reketaka\helps\common\helpers\Bh;
use reketaka\helps\modules\dictionaries\models\DictionariesName;
use reketaka\helps\modules\dictionaries\models\DictionariesValue;
use yii\console\Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;

class DictionariesController extends Controller{

    /**
     * Создает новый справочник
     * @param $alias
     * @param bool $title
     * @return bool
     */
    public function actionCreate($alias, $title=false){

        $dictionary = new DictionariesName([
            'alias'=>$alias,
            'title' =>$title
        ]);

        $dictionary->save();

        if($dictionary->hasErrors()){
            echo "Произошли ошибки:".PHP_EOL;
            foreach($dictionary->getErrorSummary(true) as $errorText){
                echo Console::ansiFormat($errorText, [Console::FG_YELLOW]).PHP_EOL;
            }
            return true;
        }

        echo "Справочник успешно создан".PHP_EOL;

        return true;
    }

    public function actionDelete($alias){
        if(!$dictionary = DictionariesName::findOne(['alias'=>$alias])){
            echo "Справочник ".Console::ansiFormat($alias, [Console::FG_YELLOW])." не найден".PHP_EOL;
            return false;
        }

        $dictionary->delete();

        echo "Справочник и все его значения успешно удален".PHP_EOL;

        return true;
    }

    public function actionDeleteValue($alias, $value){
        if(!$dictionary = DictionariesName::findOne(['alias'=>$alias])){
            echo "Справочник ".Console::ansiFormat($alias, [Console::FG_YELLOW])." не найден".PHP_EOL;
            return false;
        }

        if(!$value = $dictionary->getDictionariesValues()->where(['value'=>$value])->one()){
            echo "Указанное значение не найдено в справочнике".PHP_EOL;
            return false;
        }

        $value->delete();

        echo "Значение ".Console::ansiFormat($value->value, [Console::FG_GREEN])." в справочнике ".Console::ansiFormat($dictionary->alias, [Console::FG_GREEN])." успешно удалено ".PHP_EOL;

        return true;
    }

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

    /**
     * Выводит список значений справочника
     * @param $alias
     * @return bool|void
     */
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

    /**
     * Добавить значение в справочник
     * @param $dAlias
     * @param $value
     * @return bool|void
     */
    public function actionAddValue($dAlias, $value){
        if(!$dictionary = DictionariesName::findOne(['alias'=>$dAlias])){
            echo "Справочник не найден".PHP_EOL;
            return;
        }

        $dValue = new DictionariesValue([
            'dictionary_id' => $dictionary->id,
            'value' => $value
        ]);

        $dValue->save();

        if($dValue->hasErrors()){
            echo "Произошли ошибки:".PHP_EOL;
            foreach($dValue->getErrorSummary(true) as $key=>$errorText){
                echo Console::ansiFormat($errorText, [Console::FG_YELLOW]).PHP_EOL;
            }
            return false;
        }

        echo "Значение ".Console::ansiFormat($value, [Console::FG_YELLOW])." успешно добавлено".PHP_EOL;
        return true;
    }
}