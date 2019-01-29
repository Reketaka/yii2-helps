<?php

use reketaka\helps\common\controllers\Migration;

/**
 * Handles the creation of table `menu_section_user`.
 * Has foreign keys to the tables:
 *
 * - `user`
 */
class m190129_072326_create_menu_section_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('menu_section_user', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'user_id' => $this->integer(),
            'order' => $this->integer(),
            'parent' => $this->integer(),
            'created_at' => $this->datetime(),
            'updated_at' => $this->datetime(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            'idx-menu_section_user-user_id',
            'menu_section_user',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-menu_section_user-user_id',
            'menu_section_user',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-menu_section_user-user_id',
            'menu_section_user'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-menu_section_user-user_id',
            'menu_section_user'
        );

        $this->dropTable('menu_section_user');
    }
}
