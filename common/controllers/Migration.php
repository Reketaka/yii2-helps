<?php


namespace reketaka\helps\common\controllers;

use yii\db\ColumnSchemaBuilder;
use yii\db\Query;
use yii\db\Schema;

class Migration extends \yii\db\Migration{

    public function createTable($table, $columns, $options = null)
    {
        if($this->db->driverName == 'mysql' && !$options){
            $options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $time = $this->beginCommand("create table $table");
        $this->db->createCommand()->createTable($table, $columns, $options)->execute();
        foreach ($columns as $column => $type) {
            if ($type instanceof ColumnSchemaBuilder && $type->comment !== null) {
                $this->db->createCommand()->addCommentOnColumn($table, $column, $type->comment)->execute();
            }
        }
        $this->endCommand($time);
    }

    public function string($length = null)
    {
        return $this->getDb()->getSchema()->createColumnSchemaBuilder(Schema::TYPE_STRING, $length)->null();
    }

    /**
     * @param $tableName
     * @param $keyName
     * @throws \yii\db\Exception
     * @return bool;
     */
    function isForeignKeyExists($tableName, $keyName)
    {
        $cnt = (new Query())
            ->createCommand()->setRawSql("
                SELECT COUNT(*) cnt
                FROM information_schema.table_constraints
                WHERE constraint_name = '{$keyName}'
                  AND table_name = '{$tableName}'")
            ->queryOne()['cnt'];
        return ($cnt != 0);
    }



}