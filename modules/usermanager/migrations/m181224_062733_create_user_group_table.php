<?php

use reketaka\helps\common\controllers\Migration;

/**
 * Handles the creation of table `user_group`.
 */
class m181224_062733_create_user_group_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user_group', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->defaultValue(null),
            'alias'=>$this->string()->defaultValue(null),
            'created_at' => $this->datetime(),
            'updated_at' => $this->datetime(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user_group');
    }
}
