<?php
namespace reketaka\helps\modules\catalog\migrations;

use reketaka\helps\common\controllers\Migration;
use reketaka\helps\modules\catalog\Module;
use yii\db\Schema;

/**
 * Handles the creation of table `reketaka_catalog_item`.
 */
class m190905_093251_create_catalog_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $module = \Yii::$app->getModule('catalog');
        $schema = \Yii::$app->getDb()->getSchema();

        $baseItemFields = [
            'id'=>[
                Module::TYPE=>$schema->createColumnSchemaBuilder(Schema::TYPE_PK)
            ],
            'title'=>[
                Module::TYPE=>$schema->createColumnSchemaBuilder(Schema::TYPE_STRING)->null()
            ],
            'uid'=>[
                Module::TYPE=>$schema->createColumnSchemaBuilder(Schema::TYPE_STRING)->null()->asIndex()
            ],
            'total_amount'=>[
                Module::TYPE=>$schema->createColumnSchemaBuilder(Schema::TYPE_INTEGER)->defaultValue(0)
            ],
            'active'=>[
                Module::TYPE=>$schema->createColumnSchemaBuilder(Schema::TYPE_SMALLINT)->defaultValue(1),
            ],
            'created_at'=>[
                Module::TYPE=>$schema->createColumnSchemaBuilder(Schema::TYPE_DATETIME)->asIndex()
            ],
            'updated_at'=>[
                Module::TYPE=>$schema->createColumnSchemaBuilder(Schema::TYPE_DATETIME)->asIndex()
            ]
        ];

        $data = [];
        foreach($baseItemFields as $fieldName=>$fieldData){
            $data[$fieldName] = $fieldData[Module::TYPE];
        }



        $this->createTable(Module::$tablePrefix."catalog_item", $data);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(Module::$tablePrefix."catalog_item");
        return true;
    }
}
