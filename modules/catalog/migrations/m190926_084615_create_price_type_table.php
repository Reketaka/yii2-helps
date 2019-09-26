<?php
namespace reketaka\helps\modules\catalog\migrations;

use reketaka\helps\common\controllers\Migration;
use reketaka\helps\common\models\db\mysql\Schema;
use reketaka\helps\modules\catalog\Module;

/**
 * Handles the creation of table `reketaka_price_type`.
 */
class m190926_084615_create_price_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(Module::$tablePrefix.'price_type', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->null(),
            'alias' => $this->string()->null(),
            'uid'=>$this->string()->null(),
            'description' => $this->string()->null(),
            'default'=>Schema::TYPE_SMALLINT." DEFAULT 0",
            'created_at' => $this->datetime(),
            'updated_at' => $this->datetime(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(Module::$tablePrefix.'price_type');
        return true;
    }
}
