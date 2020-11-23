<?php

use yii\db\Migration;

/**
 * Class m201120_151254_gift_months
 */
class m201120_151254_gift_months extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('gift_months', [
            'id' => $this->primaryKey(),
            'monthId' => $this->integer(),
            'giftId' => $this->integer(),
        ]);

        $this->addForeignKey(
            'fk_giftMonths_months_monthId',
            'gift_months',
            'monthId',
            'months',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_giftMonths_months_giftId',
            'gift_months',
            'giftId',
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
        $this->dropForeignKey(
            'fk_giftMonths_months_monthId',
            'gift_months'
        );

        $this->dropForeignKey(
            'fk_giftMonths_months_giftId',
            'gift_months'
        );

        $this->dropTable('gift_months');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201120_151254_gift_months cannot be reverted.\n";

        return false;
    }
    */
}
