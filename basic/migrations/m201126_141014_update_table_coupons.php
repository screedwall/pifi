<?php

use yii\db\Migration;

/**
 * Class m201126_141014_update_table_coupons
 */
class m201126_141014_update_table_coupons extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('coupons', 'courseId');
        $this->addColumn('coupons', 'monthId', $this->integer());
        $this->addColumn('coupons', 'rest', $this->integer());
        $this->addColumn('coupons', 'code', $this->string());
        $this->addColumn('coupons', 'discount', $this->double());

        $this->addForeignKey(
            'fk_coupons_months',
            'coupons',
            'monthId',
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
            'fk_coupons_months',
            'coupons'
        );

        $this->addColumn('coupons', 'courseId', $this->integer());
        $this->dropColumn('coupons', 'monthId');
        $this->dropColumn('coupons', 'rest');
        $this->dropColumn('coupons', 'code');
        $this->dropColumn('coupons', 'discount');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201126_141014_update_table_coupons cannot be reverted.\n";

        return false;
    }
    */
}
