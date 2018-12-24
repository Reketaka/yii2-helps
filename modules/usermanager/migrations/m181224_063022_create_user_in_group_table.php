<?php

use reketaka\helps\common\controllers\Migration;

/**
 * Handles the creation of table `user_in_group`.
 */
class m181224_063022_create_user_in_group_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user_in_group', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'group_id' => $this->integer(),
        ]);

        $this->createIndex('idx-user_in_group-user_id', 'user_in_group', 'user_id');
        $this->createIndex('idx-user_in_group-group_id', 'user_in_group', 'group_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-user_in_group-user_id', 'user_in_group');
        $this->dropIndex('idx-user_in_group-group_id', 'user_in_group');

        $this->dropTable('user_in_group');
    }
}
