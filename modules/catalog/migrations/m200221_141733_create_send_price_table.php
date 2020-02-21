<?php
namespace reketaka\helps\modules\catalog\migrations;

use reketaka\helps\common\controllers\Migration;

/**
 * Handles the creation of table `send_price`.
 */
class m200221_141733_create_send_price_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('send_price', [
            'id' => $this->primaryKey(),
            'emails' => $this->string()->defaultValue(null),
            'round_price' => $this->integer(1)->defaultValue(0),
            'with_active' => $this->integer(1)->defaultValue(0),
            'active' => $this->integer(1)->defaultValue(0),
            'file_path'=>$this->string()->defaultValue(null),
            'last_send_date'=>$this->dateTime(),
            'discount_id'=>$this->integer()->defaultValue(null)->asIndex(),
            'email_header'=>$this->string()->defaultValue(null),
            'email_content'=>$this->string()->defaultValue(null)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('send_price');
    }
}
