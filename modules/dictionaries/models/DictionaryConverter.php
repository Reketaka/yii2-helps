<?php

namespace reketaka\helps\modules\dictionaries\models;

use common\helpers\BaseHelper;
use yii\base\Exception;
use yii\base\Model;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;

/**
 * Class DictionaryConverter
 *
 * Пример вызова
 * ```php
 *  $dc = (new DictionaryConverter([
 *       'oldTableStatus' => 'affiliate_status',
 *       'dictionaryName' => 'affiliate_status_dic_test',
 *       'dictionaryTitle'=>'Статус обработки заявки',
 *       'accordance' => [
 *          'id'=>'old_id',
 *          'title'=>'old_title',
 *          'alias'=>'old_alias'
 *       ],
 *       'dataTablesChanges' => [
 *          'loan_request'=>['affiliate_status_id']
 *       ],
 *       'debug'=>false
 *       'enableLogs' => true
 *   ]))->run();
 * ```
 *
 * @package reketaka\helps\modules\dictionaries\models
 */
class DictionaryConverter extends Model{

    CONST NEW_ID = 'new_id';
    CONST ALIAS = 'alias';
    CONST TITLE = 'title';
    CONST ID = 'id';

    /**
     * Название старой индексной таблицы
     * @var $oldTableStatus
     */
    public $oldTableStatus;

    /**
     * Title нового справочника
     * @var $dictionaryTitle
     */
    public $dictionaryTitle;
    /**
     * Название нового справочника
     * @var $dictionaryName
     */
    public $dictionaryName;

    /**
     * Массив соответствий где ключи колонки DictionaryValue а значения колонки старой индексной таблицы
     * @var $accordance[]
     */
    public $accordance = [];

    /**
     * Включает отображение логов рекомендуется используется в консольном режиме
     * @var bool
     */
    public $enableLogs = false;

    public $debug = true;

    /**
     * Массив с указанием таблиц где использовались старые индексы ключи таблица значения колонки
     * @var $dataTablesChanges[]
     */
    public $dataTablesChanges = [];

    private $oldStatuses = [];

    private function loadOldStatuses(){
        $this->oldStatuses = (new Query())->select(array_values($this->accordance))->from($this->oldTableStatus)->createCommand()->queryAll();

        return $this;
    }

    private function showLog($msg){
        if(!$this->enableLogs){
            return true;
        }

        echo $msg.PHP_EOL;
        return true;
    }

    /**
     * Возвращает следующий id записи в DictionaryValue
     * @return int|mixed
     */
    private function getNextInsertIdDictionaryValue(){
        $dictionaryValue = DictionariesValue::find()->select(['id'=>'MAX(`id`)'])->one();

        if(!$dictionaryValue){
            return 1;
        }

        return $dictionaryValue->id+1;
    }

    private function createNewDictionary(){

        if(DictionariesName::findOne(['alias'=>$this->dictionaryName])){
            $this->showLog("Справочник ".Console::ansiFormat($this->dictionaryName, [Console::FG_YELLOW])." уже создан");
            return $this;
        }

        $this->showLog("Создаем новый справочник данных ".Console::ansiFormat($this->dictionaryName, [Console::FG_YELLOW]));

        $dictionary = new DictionariesName([
            'alias'=>$this->dictionaryName,
            'title'=>$this->dictionaryTitle
        ]);

        if(!$dictionary->validate()){
            $this->showLog(implode(PHP_EOL, $dictionary->getErrorSummary(true)));
            throw new Exception("Can't create dictionary");
        }

        $dictionary->save();

        foreach($this->oldStatuses as $statusData){
            $dictionaryValue = new DictionariesValue([
                'dictionary_id' => $dictionary->id,
                'value'=>$statusData[$this->accordance[self::TITLE]],
                'alias'=>$statusData[$this->accordance[self::ALIAS]]
            ]);

            if($statusData[$this->accordance[self::ID]] == ($lastId = $this->getNextInsertIdDictionaryValue())){
                $dictionaryValue->id = $lastId+1;
            }

            if(!$dictionaryValue->validate()){
                $this->showLog(implode(PHP_EOL, $dictionaryValue->getErrorSummary(true)));
                throw new Exception("Can't create DictionaryValue");
            }


            $dictionaryValue->save();
        }

        return $this;
    }

    /**
     * Производит соответсвтие старых и новых статусов
     */
    private function mergeStatus(){
        $oldStatusesAlias = ArrayHelper::map($this->oldStatuses, 'alias', 'title');

        foreach($this->oldStatuses as $key=>$oldStatusItem){
            if(!array_key_exists($oldStatusItem[$this->accordance['alias']], $oldStatusesAlias)){
                continue;
            }

            $newStatusId = DictionariesHelper::findInValues($this->dictionaryName, $oldStatusItem[$this->accordance['alias']], 'alias', 'id');

            $this->oldStatuses[$key][self::NEW_ID] = $newStatusId;
        }

        return $this;
    }

    private function findOldForeignKeys(){
        /**
         * Ищем foreignKey на указанные колонки
         */
        foreach($this->dataTablesChanges as $tableName=>$columns) {
            foreach ($columns as $columnName) {

                $sql = "SELECT
    TABLE_NAME,COLUMN_NAME,CONSTRAINT_NAME, REFERENCED_TABLE_NAME,REFERENCED_COLUMN_NAME
FROM
    INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE
        REFERENCED_TABLE_NAME = '{$this->oldTableStatus}' AND
        TABLE_NAME = '$tableName' AND
        REFERENCED_COLUMN_NAME = 'id'";

                $foreignData = \Yii::$app->db->createCommand($sql)->queryOne();

                if(!$foreignData){
                    continue;
                }

                $this->showLog("Найдена установка foreignKey на колонку ".Console::ansiFormat($columnName, [Console::FG_YELLOW])." в таблице ".Console::ansiFormat($tableName, [Console::FG_YELLOW])." удаляем её");


                if(!$this->debug) {
                    try {
                        \Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=0;")->execute();
                        \Yii::$app->db->createCommand()->dropForeignKey($foreignData['CONSTRAINT_NAME'],
                            $foreignData['TABLE_NAME'])->execute();
                        \Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=1;")->execute();
                    }catch (\Exception $exception){
                        $this->showLog("Не смогли удалить или ключей не существует");
                        continue;
                    }

                }

            }
        }

        return $this;
    }

    private function statusChange(){
        $transaction = \Yii::$app->db->beginTransaction();

        $newStatusDataKeyId = DictionariesHelper::getValues($this->dictionaryName, 'id');
        $oldStatusIds = ArrayHelper::getColumn($this->oldStatuses, 'id');

        try {

            foreach($this->dataTablesChanges as $tableName=>$columns){
                foreach($columns as $columnName){

                    $this->showLog("Начинаем менять статусы в таблице ".Console::ansiFormat($tableName, [Console::FG_GREEN])." в колонке ".Console::ansiFormat($columnName, [Console::FG_YELLOW]));

                    foreach($this->oldStatuses as $oldStatusData){
                        if(array_key_exists($oldStatusData['id'], $newStatusDataKeyId)){
                            echo Console::ansiFormat("Пропускаем этот статус потому что старый id совпадает с новым", [Console::FG_YELLOW]).PHP_EOL;
                            continue;
                        }

                        $sqlUpdate = \Yii::$app->db->createCommand()->update($tableName, [$columnName=>$oldStatusData['id']], "$columnName = {$oldStatusData['new_id']}");

                        echo $sqlUpdate->getRawSql().PHP_EOL;

                        if(!$this->debug){
                            $sqlUpdate->execute();
                        }

                    }

                }
            }

        }catch (\Exception $exception){
            $transaction->rollBack();
        }

        $transaction->commit();

        return $this;
    }

    private function setNewForeignKeys(){
        foreach($this->dataTablesChanges as $tableName=>$columns) {
            foreach ($columns as $columnName) {

                $foreignKeyName = "fk-$tableName-$columnName-".DictionariesValue::tableName()."-id";

                $this->showLog("Устанавливаем foreignKey ".Console::ansiFormat($tableName, [Console::FG_YELLOW])." на колонку ".Console::ansiFormat($columnName, [Console::FG_YELLOW])." ссылаемся на таблицу ".Console::ansiFormat(DictionariesValue::tableName(), [Console::FG_YELLOW])." колонка ".Console::ansiFormat("id", [Console::FG_YELLOW]));

                if(!$this->debug) {
                    try {
                        \Yii::$app->db->createCommand()->addForeignKey($foreignKeyName, $tableName, $columnName,
                            DictionariesValue::tableName(), 'id')->execute();
                    }catch (\Exception $exception){
                        $this->showLog("Ошибки создания новых ForeignKeys ".Console::ansiFormat($exception->getMessage(), [Console::FG_GREEN]));
                        continue;
                    }
                }

            }
        }

        return $this;
    }

    /**
     * Проверка что все необходимые данные переданны и можно начинать работать
     */
    private function checkToWork(){
        if(!$this->oldTableStatus){
            throw new Exception('Empty variable $oldTableStatus');
        }

        if(!$this->dictionaryName){
            throw new Exception('Empty variable $dictionaryName');
        }

        if(!$this->dictionaryTitle){
            throw new Exception('Empty variable $dictionaryTitle');
        }

        if(!$this->accordance){
            throw new Exception('Empty variable $accordance');
        }

        if(!array_key_exists(self::ID, $this->accordance) || !array_key_exists(self::TITLE, $this->accordance) || !array_key_exists(self::ALIAS, $this->accordance)){
            throw new Exception('Accordance variable not full');
        }

        if(!$this->dataTablesChanges){
            throw new Exception('Empty variable $dataTablesChanges');
        }
    }

    private function showNewRelationPHPCode(){
        if(!$this->enableLogs){
            return $this;
        }

        foreach($this->dataTablesChanges as $tableName=>$columns){
            foreach($columns as $column){

                echo "Таблица ".Console::ansiFormat($tableName, [Console::FG_YELLOW])." колонка ".Console::ansiFormat($column, [Console::FG_GREEN])." ";

                $phpCode = 'return DictionariesHelper::getRelationWith($this, \''.$this->dictionaryName.'\', \''.$column.'\');';

                echo "Замените связь на ".Console::ansiFormat($phpCode, [Console::FG_YELLOW]).PHP_EOL;

            }


        }

    }

    public function run(){

        $this->checkToWork();

        $this->loadOldStatuses()
            ->createNewDictionary()
            ->mergeStatus()
            ->findOldForeignKeys()
            ->statusChange()
            ->setNewForeignKeys()
            ->showNewRelationPHPCode();


    }
}