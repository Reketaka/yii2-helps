<?php
namespace reketaka\helps\modules\dictionaries\migrations;

use reketaka\helps\common\controllers\Migration;

/**
 * Handles the creation of table `dictionaries_name`.
 * Has foreign keys to the tables:
 *
 * - `dictionaries_name`
 */
class m190513_091622_create_dictionaries_name_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('dictionaries_name', [
            'id' => $this->primaryKey(),
            'dictionary_id' => $this->integer(),
            'value' => $this->string()->null(),
        ]);

        // creates index for column `dictionary_id`
        $this->createIndex(
            'idx-dictionaries_name-dictionary_id',
            'dictionaries_name',
            'dictionary_id'
        );

        // add foreign key for table `dictionaries_name`
        $this->addForeignKey(
            'fk-dictionaries_name-dictionary_id',
            'dictionaries_name',
            'dictionary_id',
            'dictionaries_name',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `dictionaries_name`
        $this->dropForeignKey(
            'fk-dictionaries_name-dictionary_id',
            'dictionaries_name'
        );

        // drops index for column `dictionary_id`
        $this->dropIndex(
            'idx-dictionaries_name-dictionary_id',
            'dictionaries_name'
        );

        $this->dropTable('dictionaries_name');

        return true;
    }
}
