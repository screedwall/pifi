<?php

use yii\db\Migration;

/**
 * Class m210117_215153_update_table_gift_months
 */
class m210117_215153_update_table_gift_months extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('gift_months', 'isShort', $this->boolean()->defaultValue('false'));
        $this->addColumn('gift_months', 'isLong', $this->boolean()->defaultValue('false'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('gift_months', 'isShort');
        $this->dropColumn('gift_months', 'isLong');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210117_215153_update_table_gift_months cannot be reverted.\n";

        return false;
    }
    */
}
