<?php

use yii\db\Migration;

/**
 * Class m200926_154247_create_table_months
 */
class m200926_154247_create_table_months extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('months', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'dateFrom' => $this->date(),
            'dateTo' => $this->date(),
            'courseId' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk_month_courses',
            'months',
            'courseId',
            'courses',
            'id',
            'CASCADE'
        );

        $this->insert('months', [
            'name' => 'Сентябрь',
            'dateFrom' => date('Y-m-d', strtotime("2020-09-01")),
            'dateTo' => date('Y-m-d', strtotime("2020-09-30")),
            'courseId' => '1',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk_month_courses',
            'months'
        );

        $this->dropTable('months');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200926_154247_create_table_months cannot be reverted.\n";

        return false;
    }
    */
}
