<?php
namespace reketaka\helps\modules\catalog\migrations;

use reketaka\helps\common\controllers\Migration;
use reketaka\helps\modules\catalog\Module;
/**
 * Class m191106_095732_add_column_to_item
 */
class m191106_095732_add_column_to_item extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Module::$tablePrefix."catalog_item", "active", $this->smallInteger()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Module::$tablePrefix."catalog_item", "active");

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191106_095732_add_column_to_item cannot be reverted.\n";

        return false;
    }
    */
}
