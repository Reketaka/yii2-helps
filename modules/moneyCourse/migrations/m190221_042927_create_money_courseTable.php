<?php
namespace reketaka\helps\modules\moneyCourse\migrations;

/**
 * Class m190221_042927_create_money_courseTable
 */
class m190221_042927_create_money_courseTable extends \yii\db\Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('reketaka_money_course', [
            'id' => $this->primaryKey(),
            'name'=>$this->string()->comment('Название'),
            'char'=>$this->string(),
            'val'=>$this->float()->defaultValue(0)->comment('Значение'),
            'date'=>$this->dateTime()
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('reketaka_money_course');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190221_042927_create_money_courseTable cannot be reverted.\n";

        return false;
    }
    */
}
