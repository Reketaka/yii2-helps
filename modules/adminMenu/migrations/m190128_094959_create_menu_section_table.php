<?php

use reketaka\helps\common\controllers\Migration;

/**
 * Handles the creation of table `menu_section`.
 */
class m190128_094959_create_menu_section_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('menu_section', [
            'id' => $this->primaryKey(),
            'alias' => $this->string(),
            'title' => $this->string(),
            'order' => $this->integer()->defaultValue(0),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('menu_section');
    }
}
