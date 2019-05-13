<?php
namespace reketaka\helps\modules\dictionaries\migrations;

use reketaka\helps\common\controllers\Migration;

/**
 * Handles the creation of table `dictionaries_value`.
 * Has foreign keys to the tables:
 *
 * - `dictionaries_name`
 */
class m190513_093111_create_dictionaries_value_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('dictionaries_value', [
            'id' => $this->primaryKey(),
            'dictionary_id' => $this->integer(),
            'value' => $this->string()->null(),
        ]);

        // creates index for column `dictionary_id`
        $this->createIndex(
            'idx-dictionaries_value-dictionary_id',
            'dictionaries_value',
            'dictionary_id'
        );

        // add foreign key for table `dictionaries_name`
        $this->addForeignKey(
            'fk-dictionaries_value-dictionary_id',
            'dictionaries_value',
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
            'fk-dictionaries_value-dictionary_id',
            'dictionaries_value'
        );

        // drops index for column `dictionary_id`
        $this->dropIndex(
            'idx-dictionaries_value-dictionary_id',
            'dictionaries_value'
        );

        $this->dropTable('dictionaries_value');
    }
}
