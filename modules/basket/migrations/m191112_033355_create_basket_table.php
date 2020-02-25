<?php

namespace reketaka\helps\modules\basket\migrations;

use reketaka\helps\common\controllers\Migration;
use reketaka\helps\common\helpers\Bh;

/**
 * Handles the creation of table `basket`.
 */
class m191112_033355_create_basket_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $basketModule = \Yii::$app->getModule('basket');

        $this->createTable('basket', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->defaultValue(null)->asIndex(),
            'session_id' => $this->string()->defaultValue(null)->asIndex(),
            'total_amount'=>$this->integer()->defaultValue(0),
            'total_price'=>$this->double()->defaultValue(0),
            'created_at' => $this->datetime()->asIndex(),
            'updated_at' => $this->datetime(),
        ]);

        $attributes = [
            'id'=>$this->primaryKey(),
            'basket_id'=>$this->integer()->asIndex(),
            'item_id'=>$this->integer()->defaultValue(null)->asIndex(),
            'title'=>$this->string()->defaultValue(null),
            'uid'=>$this->string()->defaultValue(null),
            'price'=>$this->double()->defaultValue(0),
            'amount'=>$this->integer()->defaultValue(0),
            'created_at'=>$this->dateTime(),
            'updated_at'=>$this->dateTime()
        ];


        foreach($basketModule->basketItemFields as $fieldName){
            $attributes[$fieldName] = $this->string();
        }

        $this->createTable('basket_item', $attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('basket');
        $this->dropTable('basket_item');
    }
}
