<?php


namespace reketaka\helps\common\controllers;

use function array_key_exists;
use common\models\BaseHelper;
use Yii;
use yii\db\ColumnSchemaBuilder;
use yii\db\Query;
use yii\db\Schema;

class Migration extends \yii\db\Migration{

    public function addColumn($table, $column, $type)
    {
        $time = $this->beginCommand("add column $column $type to table $table");
        $this->db->createCommand()->addColumn($table, $column, $type)->execute();
        if ($type instanceof ColumnSchemaBuilder && $type->comment !== null) {
            $this->db->createCommand()->addCommentOnColumn($table, $column, $type->comment)->execute();
        }

        if(method_exists($type, "isIndex") && $type->isIndex()){
            $name = "idx-$table-$column";
            $unique = $type->getUniqIndex();

            $time = $this->beginCommand('create' . ($unique ? ' unique' : '') . " index $name on $table (" . $column . ')');
            $this->db->createCommand()->createIndex($name, $table, $column, $unique)->execute();
            $this->endCommand($time);
        }


        $this->endCommand($time);
    }

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

            if(method_exists($type, "isIndex") && $type->isIndex()){
                $name = "idx-$table-$column";
                $unique = $type->getUniqIndex();

                $time = $this->beginCommand('create' . ($unique ? ' unique' : '') . " index $name on $table (" . $column . ')');
                $this->db->createCommand()->createIndex($name, $table, $column, $unique)->execute();
                $this->endCommand($time);
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

    public function hasColumn($table, $columnName){
        $query = Yii::$app->db->createCommand("SHOW COLUMNS FROM `$table` LIKE '$columnName'")->queryOne();

        if(!array_key_exists('Field', $query)){
            return false;
        }

        return true;
    }


}