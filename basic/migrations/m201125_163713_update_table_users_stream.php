<?php

use yii\db\Migration;

/**
 * Class m201125_163713_update_table_users_stream
 */
class m201125_163713_update_table_users_stream extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('users_stream', 'remains', $this->integer());
        $this->dropColumn('users_stream', 'boughtId');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('users_stream', 'remains');
        $this->addColumn('users_stream', 'boughtId', $this->integer());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201125_163713_update_table_users_stream cannot be reverted.\n";

        return false;
    }
    */
}
