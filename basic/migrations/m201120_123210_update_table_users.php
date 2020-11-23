<?php

use yii\db\Migration;

/**
 * Class m201120_123210_update_table_users
 */
class m201120_123210_update_table_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('users', 'createdAt', $this->dateTime());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('users', 'createdAt');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201120_123210_update_table_users cannot be reverted.\n";

        return false;
    }
    */
}
