<?php

namespace reketaka\helps\modules\catalog\migrations;

use reketaka\helps\common\controllers\Migration;

/**
 * Handles the creation of table `discount`.
 */
class m191116_093304_create_discount_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('discount', [
            'id' => $this->primaryKey(),
            'title'=>$this->string(),
            'alias'=>$this->string(),
            'value'=>$this->integer()->defaultValue(0),
            'active'=>$this->smallInteger()->defaultValue(0),
            'created_at'=>$this->dateTime()->asIndex(),
            'updated_at'=>$this->dateTime()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('discount');
        return true;
    }
}
