<?php
namespace reketaka\helps\modules\catalog\migrations;

use reketaka\helps\common\controllers\Migration;
use reketaka\helps\modules\catalog\Module;

/**
 * Handles the creation of table `reketaka_store`.
 */
class m190905_100529_create_store_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(Module::$tablePrefix.'store', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'uid' => $this->string()->asIndex(),
            'comment' => $this->string()->null(),
            'created_at' => $this->datetime()->asIndex(),
            'updated_at' => $this->datetime()->asIndex()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(Module::$tablePrefix.'store');

        return true;
    }
}
