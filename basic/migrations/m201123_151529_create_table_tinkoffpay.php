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
            'amount' => $this->double(),
            'status' => $this->string(),
            'createdAt' => $this->dateTime(),
            'courseId' => $this->integer(),
            'monthId' => $this->integer(),
            'userId' => $this->integer(),
            'type' => $this->string(),
        ]);

        $this->addForeignKey(
            'fk_tinkoffpay_users',
            'tinkoffpay',
            'userId',
            'users',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_tinkoffpay_courses',
            'tinkoffpay',
            'courseId',
            'courses',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_tinkoffpay_months',
            'tinkoffpay',
            'monthId',
            'months',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('tinkoffpay');
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
