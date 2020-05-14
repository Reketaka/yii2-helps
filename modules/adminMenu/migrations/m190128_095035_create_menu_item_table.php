<?php

use reketaka\helps\common\controllers\Migration;

/**
 * Handles the creation of table `menu_item`.
 * Has foreign keys to the tables:
 *
 * - `menu_section`
 */
class m190128_095035_create_menu_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('menu_item', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'alias' => $this->string(),
            'url' => $this->string(),
            'section_id' => $this->integer(),
            'order' => $this->integer()->defaultValue(0),
        ]);

        // creates index for column `section_id`
        $this->createIndex(
            'idx-menu_item-section_id',
            'menu_item',
            'section_id'
        );

        // add foreign key for table `menu_section`
        $this->addForeignKey(
            'fk-menu_item-section_id',
            'menu_item',
            'section_id',
            'menu_section',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `menu_section`
        $this->dropForeignKey(
            'fk-menu_item-section_id',
            'menu_item'
        );

        // drops index for column `section_id`
        $this->dropIndex(
            'idx-menu_item-section_id',
            'menu_item'
        );

        $this->dropTable('menu_item');
    }
}
