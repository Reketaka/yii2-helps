<?php

use reketaka\helps\common\controllers\Migration;

/**
 * Handles the creation of table `menu_item_roles`.
 * Has foreign keys to the tables:
 *
 * - `menu_item`
 */
class m190128_095136_create_menu_item_roles_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('menu_item_roles', [
            'id' => $this->primaryKey(),
            'menu_item_id' => $this->integer(),
            'role_name' => $this->string(),
        ]);

        // creates index for column `menu_item_id`
        $this->createIndex(
            'idx-menu_item_roles-menu_item_id',
            'menu_item_roles',
            'menu_item_id'
        );

        // add foreign key for table `menu_item`
        $this->addForeignKey(
            'fk-menu_item_roles-menu_item_id',
            'menu_item_roles',
            'menu_item_id',
            'menu_item',
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
            'fk-menu_item_roles-menu_item_id',
            'menu_item_roles'
        );

        // drops index for column `menu_item_id`
        $this->dropIndex(
            'idx-menu_item_roles-menu_item_id',
            'menu_item_roles'
        );

        $this->dropTable('menu_item_roles');
    }
}
