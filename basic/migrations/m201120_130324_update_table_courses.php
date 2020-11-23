<?php

use yii\db\Migration;

/**
 * Class m201120_130324_update_table_courses
 */
class m201120_130324_update_table_courses extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('courses', 'price');
        $this->dropColumn('courses', 'dateFrom');
        $this->dropColumn('courses', 'dateTo');
        $this->addColumn('courses', 'isVisible', $this->boolean());
        $this->addColumn('courses', 'isSpec', $this->boolean());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('courses', 'price', $this->double());
        $this->addColumn('courses', 'dateFrom', $this->date());
        $this->addColumn('courses', 'dateTo', $this->date());
        $this->dropColumn('courses', 'isVisible');
        $this->dropColumn('courses', 'isSpec');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201120_130324_update_table_courses cannot be reverted.\n";

        return false;
    }
    */
}
