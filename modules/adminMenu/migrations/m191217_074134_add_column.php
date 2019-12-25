<?php

use reketaka\helps\common\controllers\Migration;

/**
 * Class m191217_074134_add_column
 */
class m191217_074134_add_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('menu_item', 'controller_uniq_id', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('menu_item', 'controller_uniq_id');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191217_074134_add_column cannot be reverted.\n";

        return false;
    }
    */
}
