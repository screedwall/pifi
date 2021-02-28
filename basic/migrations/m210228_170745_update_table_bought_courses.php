<?php

use yii\db\Migration;
use app\models\BoughtCourses;

/**
 * Class m210228_170745_update_table_bought_courses
 */
class m210228_170745_update_table_bought_courses extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->addColumn(BoughtCourses::tableName(), 'isDemo', $this->boolean()->defaultValue('false'));
        $this->addColumn(BoughtCourses::tableName(), 'isDemoContinued', $this->boolean()->defaultValue('false'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(BoughtCourses::tableName(), 'isDemo');
        $this->dropColumn(BoughtCourses::tableName(), 'isDemoContinued');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210228_170745_update_table_bought_courses cannot be reverted.\n";

        return false;
    }
    */
}
