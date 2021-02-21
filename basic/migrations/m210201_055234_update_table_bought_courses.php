<?php

use yii\db\Migration;

/**
 * Class m210201_055234_update_table_bought_courses
 */
class m210201_055234_update_table_bought_courses extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bought_courses', 'giftedByMonthId', $this->integer());
        $this->addColumn('bought_courses', 'giftedByBC', $this->integer());
        $this->dropColumn('bought_courses', 'isStream');
        $this->addColumn('bought_courses', 'streamId', $this->integer());

        $this->addForeignKey(
            'fk_bought_courses-users_stream',
            'bought_courses',
            'streamId',
            'users_stream',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_bc-bc',
            'bought_courses',
            'giftedByBC',
            'bought_courses',
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
            'fk_bought_courses-users_stream',
            'bought_courses'
        );

        $this->dropForeignKey(
            'fk_bc-bc',
            'bought_courses'
        );

        $this->dropColumn('bought_courses', 'giftedByMonthId');
        $this->dropColumn('bought_courses', 'giftedByBC');
        $this->dropColumn('bought_courses', 'streamId');
        $this->addColumn('bought_courses', 'isStream', $this->boolean()->defaultValue('false'));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210201_055234_update_table_bought_courses cannot be reverted.\n";

        return false;
    }
    */
}
