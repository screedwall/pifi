<?php

use yii\db\Migration;

/**
 * Class m201127_154207_prepare_users_import
 */
class m201127_154207_prepare_users_import extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->delete('users');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201127_154207_prepare_users_import cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201127_154207_prepare_users_import cannot be reverted.\n";

        return false;
    }
    */
}
