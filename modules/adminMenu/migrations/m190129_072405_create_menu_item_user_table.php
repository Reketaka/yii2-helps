<?php

use reketaka\helps\common\controllers\Migration;

/**
 * Handles the creation of table `menu_item_user`.
 * Has foreign keys to the tables:
 *
 * - `menu_item`
 * - `menu_section_user`
 */
class m190129_072405_create_menu_item_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('menu_item_user', [
            'id' => $this->primaryKey(),
            'menu_item_id' => $this->integer(),
            'menu_section_id' => $this->integer(),
            'order' => $this->integer(),
            'user_id'=>$this->integer()
        ]);

        $this->createIndex('idx-menu_item_user-user_id', 'menu_item_user', 'user_id');

        // creates index for column `menu_item_id`
        $this->createIndex(
            'idx-menu_item_user-menu_item_id',
            'menu_item_user',
            'menu_item_id'
        );

        // add foreign key for table `menu_item`
        $this->addForeignKey(
            'fk-menu_item_user-menu_item_id',
            'menu_item_user',
            'menu_item_id',
            'menu_item',
            'id',
            'CASCADE'
        );

        // creates index for column `menu_section_id`
        $this->createIndex(
            'idx-menu_item_user-menu_section_id',
            'menu_item_user',
            'menu_section_id'
        );

        // add foreign key for table `menu_section_user`
        $this->addForeignKey(
            'fk-menu_item_user-menu_section_id',
            'menu_item_user',
            'menu_section_id',
            'menu_section_user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `menu_item`
        $this->dropForeignKey(
            'fk-menu_item_user-menu_item_id',
            'menu_item_user'
        );

        // drops index for column `menu_item_id`
        $this->dropIndex(
            'idx-menu_item_user-menu_item_id',
            'menu_item_user'
        );

        // drops foreign key for table `menu_section_user`
        $this->dropForeignKey(
            'fk-menu_item_user-menu_section_id',
            'menu_item_user'
        );

        // drops index for column `menu_section_id`
        $this->dropIndex(
            'idx-menu_item_user-menu_section_id',
            'menu_item_user'
        );

        $this->dropTable('menu_item_user');
    }
}
