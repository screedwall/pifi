<?php

use yii\db\Migration;

/**
 * Class m201127_131644_update_table_users
 */
class m201127_131644_update_table_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('users', 'description', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('users', 'description', $this->string());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201127_131644_update_table_users cannot be reverted.\n";

        return false;
    }
    */
}
