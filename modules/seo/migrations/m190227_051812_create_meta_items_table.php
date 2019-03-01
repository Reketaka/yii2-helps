<?php
namespace reketaka\helps\modules\seo\migrations;

use reketaka\helps\common\controllers\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `meta_items`.
 */
class m190227_051812_create_meta_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('reketaka_meta_items', [
            'id' => $this->primaryKey(),
            'created_at'=>$this->dateTime(),
            'updated_at'=>$this->dateTime()
        ]);

        $this->addColumn('reketaka_meta_items', 'item_id', Schema::TYPE_INTEGER." DEFAULT NULL");
        $this->addColumn('reketaka_meta_items', 'modelName', Schema::TYPE_STRING.' DEFAULT NULL');
        $this->addColumn('reketaka_meta_items', 'path', Schema::TYPE_STRING.' DEFAULT NULL');
        $this->addColumn('reketaka_meta_items', 'h1', Schema::TYPE_STRING.' DEFAULT NULL');
        $this->addColumn('reketaka_meta_items', 'title', Schema::TYPE_STRING.' DEFAULT NULL');
        $this->addColumn('reketaka_meta_items', 'keywords', Schema::TYPE_STRING.' DEFAULT NULL');
        $this->addColumn('reketaka_meta_items', 'description', Schema::TYPE_STRING.' DEFAULT NULL');

        $this->createIndex('idx-reketaka_meta_items-item_id', 'reketaka_meta_items', 'item_id');
        $this->createIndex('idx-reketaka_meta_items-modelName', 'reketaka_meta_items', 'modelName');
        $this->createIndex('idx-reketaka_meta_items-path', 'reketaka_meta_items', 'path');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('reketaka_meta_items');
        return true;
    }
}
