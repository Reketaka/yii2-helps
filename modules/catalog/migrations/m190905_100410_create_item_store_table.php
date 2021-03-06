<?php
namespace reketaka\helps\modules\catalog\migrations;

use reketaka\helps\common\controllers\Migration;
use reketaka\helps\modules\catalog\Module;

/**
 * Handles the creation of table `reketaka_item_store`.
 */
class m190905_100410_create_item_store_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(Module::$tablePrefix.'item_store', [
            'id' => $this->primaryKey(),
            'item_id' => $this->integer()->asIndex(),
            'store_id'=>$this->integer()->asIndex(),
            'amount' => $this->integer()->defaultValue(0),
            'created_at' => $this->datetime()->asIndex(),
            'updated_at' => $this->datetime()->asIndex()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(Module::$tablePrefix.'item_store');

        return true;
    }
}
