<?php
namespace reketaka\helps\modules\catalog\migrations;

use reketaka\helps\common\controllers\Migration;
use reketaka\helps\common\models\db\mysql\Schema;
use reketaka\helps\modules\catalog\Module;

/**
 * Handles the creation of table `reketaka_item_price`.
 */
class m190926_090138_create_item_price_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(Module::$tablePrefix.'item_price', [
            'id' => $this->primaryKey(),
            'item_id' => $this->integer()->asIndex(),
            'price_type_id' => $this->integer()->asIndex(),
            'price'=>Schema::TYPE_DOUBLE." DEFAULT 0",
            'created_at' => $this->datetime(),
            'updated_at' => $this->datetime(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(Module::$tablePrefix.'item_price');
    }
}
