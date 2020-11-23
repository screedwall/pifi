<?php

use yii\db\Migration;

/**
 * Class m201123_142157_create_table_coupons
 */
class m201123_142157_create_table_coupons extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('coupons', [
            'id' => $this->primaryKey(),
            'courseId' => $this->integer(),
            'count' => $this->integer(),
            'unique' => $this->boolean(),
        ]);

        $this->addForeignKey(
            'fk_coupons_courses',
            'coupons',
            'courseId',
            'courses',
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
            'fk_coupons_courses',
            'coupons'
        );

        $this->dropTable('coupons');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201123_142157_create_table_coupons cannot be reverted.\n";

        return false;
    }
    */
}
