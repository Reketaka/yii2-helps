<?php

namespace reketaka\helps\common\helpers;

use reketaka\helps\common\models\db\mysql\Schema;
use yii\base\Model;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\Console;
use reketaka\helps\common\helpers\Bh;

class ZipTableCreator extends Model {

    public $table;
    public $field;

    private function getTableName(){
        return "{$this->table}_{$this->field}_zip";
    }

    private function transferData(){

        $maxId = (new Query)->select(new Expression("MAX(id) as 'max_id'"))->from($this->table)->createCommand()->queryOne();
        $maxId = $maxId['max_id'];

        $partSize = 6000;

        $parts = $maxId/$partSize;
        $parts = (int)$parts;
        if($maxId%$partSize > 0){
            $parts++;
        }

        for($a=1;$a<=$parts;$a++){
            $start = ($a-1)*$partSize;

            $values = (new Query)
                ->select([
                    new Expression("id as 'model_id'"),
                    new Expression("COMPRESS({$this->field}) as 'value'")
                ])
                ->from($this->table)
                ->limit($partSize)
                ->offset($start)
                ->createCommand()
                ->queryAll();

            echo "Пакетная выборка использовано памяти ".Console::ansiFormat(Bh::getMemoryUsage(), [Console::FG_YELLOW])." пакет ".Console::ansiFormat($a, [Console::FG_YELLOW]). " из ".Console::ansiFormat($parts, [Console::FG_YELLOW]).PHP_EOL;

            if(!$values){
                continue;
            }

            $values = array_chunk($values, 100);

            foreach($values as $subValues){
                \Yii::$app->db->createCommand()->batchInsert($this->getTableName(), ['model_id', 'value'], $subValues)->execute();
            }

        }

        return true;
    }

    public function up(){
        $schema = \Yii::$app->db->schema;
        $schemaBuilder =$schema->createQueryBuilder();

        $createTable = $schemaBuilder->createTable($this->getTableName(), [
            'id'=>$schema->createColumnSchemaBuilder(Schema::TYPE_PK),
            'model_id'=>$schema->createColumnSchemaBuilder(Schema::TYPE_INTEGER),
            'value'=>"blob default null",
        ], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");

        \Yii::$app->db->createCommand($createTable)->execute();

        \Yii::$app->db->createCommand($schemaBuilder->createIndex("idx-{$this->getTableName()}-model_id", $this->getTableName(), "model_id", true))->execute();

        echo "Zip таблица создана ".Console::ansiFormat($this->getTableName(), [Console::FG_YELLOW]).PHP_EOL;

        echo "Начинаем перенос данных в новую таблицу".PHP_EOL;
        $this->transferData();
        echo "Перенос данных закончен".PHP_EOL;

        echo "Начинаем удаления колонки из исходной таблицы ".Console::ansiFormat($this->table, [Console::FG_YELLOW]).PHP_EOL;

        \Yii::$app->db->createCommand($schemaBuilder->dropColumn($this->table, $this->field))->execute();

        echo "Удаление колонки из исходной таблицы ".Console::ansiFormat($this->table, [Console::FG_YELLOW])." ".Console::ansiFormat("завершено", [Console::FG_GREEN]).PHP_EOL;


        return true;
    }

    public function down(){
        $schema = \Yii::$app->db->schema;
        $schemaBuilder =$schema->createQueryBuilder();

        \Yii::$app->db->createCommand()->dropTable($this->getTableName())->execute();

        echo "Zip таблица удалена".PHP_EOL;

        \Yii::$app->db->createCommand($schemaBuilder->addColumn($this->table, $this->field, $schema->createColumnSchemaBuilder(Schema::TYPE_TEXT)->null()))->execute();

        echo "Колонка ".Console::ansiFormat($this->field, [Console::FG_YELLOW])." добавлена в исходную таблицу ".Console::ansiFormat($this->table, [Console::FG_YELLOW]).PHP_EOL;

        return true;

    }
}