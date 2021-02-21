<?php

use yii\db\Migration;

/**
 * Class m210211_053330_update_table_tinkoffpay
 */
class m210211_053330_update_table_tinkoffpay extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(\app\models\TinkoffPay::tableName(), 'response', $this->json());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(\app\models\TinkoffPay::tableName(), 'response');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210211_053330_update_table_tinkoffpay cannot be reverted.\n";

        return false;
    }
    */
}
