<?php
namespace reketaka\helps\modules\catalog\migrations;

use reketaka\helps\common\controllers\Migration;
use reketaka\helps\modules\catalog\Module;

/**
 * Handles the creation of table `catalog`.
 */
class m191103_142235_create_catalog_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(Module::$tablePrefix."catalog", [
            'id' => $this->primaryKey(),
            'title'=>$this->string(),
            'alias'=>$this->string(),
            'uid'=>$this->string(),
            'description'=>$this->text()->defaultValue(null),
            'parent_id'=>$this->integer()->defaultValue(null),
            'created_at'=>$this->dateTime(),
            'updated_at'=>$this->dateTime()
        ]);

        $this->addColumn(Module::$tablePrefix."catalog_item", "catalog_id", $this->integer()->asIndex());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(Module::$tablePrefix."catalog");
        $this->dropColumn(Module::$tablePrefix."catalog_item", "catalog_id");
        return true;
    }
}
