<?php

use reketaka\helps\common\controllers\Migration;

/**
 * Class m191217_060735_add_column
 */
class m191217_060735_add_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('menu_item', 'icon', $this->string());
        $this->addColumn('menu_section', 'icon', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('menu_item', 'icon');
        $this->dropColumn('menu_section', 'icon');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191217_060735_add_column cannot be reverted.\n";

        return false;
    }
    */
}
