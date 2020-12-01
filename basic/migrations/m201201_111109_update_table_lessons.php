<?php

use yii\db\Migration;

/**
 * Class m201201_111109_update_table_lessons
 */
class m201201_111109_update_table_lessons extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('lessons', 'description', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('lessons', 'description', $this->string());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201201_111109_update_table_lessons cannot be reverted.\n";

        return false;
    }
    */
}
