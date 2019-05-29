<?php
namespace reketaka\helps\common\migrations;

use reketaka\helps\common\controllers\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `reg`.
 */
class m190222_042158_create_reg_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('regedit', [
            'id' => $this->primaryKey(),
            'var' => $this->string(),
            'val' => $this->string(),
        ]);

        $this->addColumn('regedit', 'rel', Schema::TYPE_INTEGER." DEFAULT 0");

        $this->createIndex('idx-regedit-rel', 'regedit', 'rel');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-regedit-rel', 'regedit');
        $this->dropTable('regedit');
        return true;
    }
}
