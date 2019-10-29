<?php
namespace reketaka\helps\modules\catalog\migrations;

use reketaka\helps\common\controllers\Migration;
use reketaka\helps\modules\catalog\Module;

/**
 * Class m191029_045057_add_column_to_catalog
 */
class m191029_045057_add_column_to_catalog extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Module::$tablePrefix.'store', 'alias', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Module::$tablePrefix.'store', 'alias');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191029_045057_add_column_to_catalog cannot be reverted.\n";

        return false;
    }
    */
}
