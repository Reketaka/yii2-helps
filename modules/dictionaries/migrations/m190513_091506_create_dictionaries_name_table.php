<?php
namespace reketaka\helps\modules\dictionaries\migrations;

use reketaka\helps\common\controllers\Migration;

/**
 * Handles the creation of table `dictionaries_name`.
 */
class m190513_091506_create_dictionaries_name_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('dictionaries_name', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->null(),
            'alias' => $this->string()->null(),
            'description' => $this->string()->null(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('dictionaries_name');
        return true;
    }
}
