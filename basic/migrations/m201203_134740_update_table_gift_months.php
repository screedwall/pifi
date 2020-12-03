<?php

use yii\db\Migration;

/**
 * Class m201203_134740_update_table_gift_months
 */
class m201203_134740_update_table_gift_months extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('gift_months', 'isExtension', $this->boolean()->defaultValue('false'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('gift_months', 'isExtension');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201203_134740_update_table_gift_months cannot be reverted.\n";

        return false;
    }
    */
}
