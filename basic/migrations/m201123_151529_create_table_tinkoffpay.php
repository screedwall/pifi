<?php

use yii\db\Migration;

/**
 * Class m201123_151529_create_table_tinkoffpay
 */
class m201123_151529_create_table_tinkoffpay extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('tinkoffpay', [
            'id' => $this->primaryKey(),
            'amount' => $this->integer(),
            'status' => $this->string(),
            'createdAt' => $this->dateTime(),
            'closedAt' => $this->dateTime(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201123_151529_create_table_tinkoffpay cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201123_151529_create_table_tinkoffpay cannot be reverted.\n";

        return false;
    }
    */
}
