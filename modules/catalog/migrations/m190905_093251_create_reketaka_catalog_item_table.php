<?php
namespace reketaka\helps\modules\catalog\migrations;

use reketaka\helps\common\controllers\Migration;
use reketaka\helps\modules\catalog\Module;

/**
 * Handles the creation of table `reketaka_catalog_item`.
 */
class m190905_093251_create_reketaka_catalog_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $module = \Yii::$app->getModule('catalog');

        $data = [];
        foreach($module->getItemsFields() as $fieldName=>$fieldData){
            $data[$fieldName] = $fieldData[Module::TYPE];
        }


        $this->createTable('reketaka_catalog_item', $data);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('reketaka_catalog_item');
        return true;
    }
}
