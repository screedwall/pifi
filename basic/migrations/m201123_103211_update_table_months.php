<?php

use yii\db\Migration;

/**
 * Class m201123_103211_update_table_months
 */
class m201123_103211_update_table_months extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('months', 'priceShort', $this->double());
        $this->addColumn('months', 'priceLong', $this->double());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('months', 'priceShort');
        $this->dropColumn('months', 'priceLong');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201123_103211_update_table_months cannot be reverted.\n";

        return false;
    }
    */
}
