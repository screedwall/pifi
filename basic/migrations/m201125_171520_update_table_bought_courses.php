<?php

use yii\db\Migration;

/**
 * Class m201125_171520_update_table_bought_courses
 */
class m201125_171520_update_table_bought_courses extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bought_courses', 'paymentId', $this->integer());
        $this->addColumn('bought_courses', 'isStream', $this->boolean());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bought_courses', 'paymentId');
        $this->dropColumn('bought_courses', 'isStream');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201125_171520_update_table_bought_courses cannot be reverted.\n";

        return false;
    }
    */
}
