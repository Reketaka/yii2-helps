<?php

namespace reketaka\helps\modules\catalog\migrations;

use reketaka\helps\common\controllers\Migration;
use reketaka\helps\modules\catalog\Module;

/**
 * Class m191108_033519_add_column_to_catalog
 */
class m191108_033519_add_column_to_catalog extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Module::$tablePrefix."catalog", 'active', $this->integer()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Module::$tablePrefix."catalog", 'active');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191108_033519_add_column_to_catalog cannot be reverted.\n";

        return false;
    }
    */
}
