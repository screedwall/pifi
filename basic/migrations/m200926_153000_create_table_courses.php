<?php

use yii\db\Migration;

/**
 * Class m200926_153000_create_table_courses
 */
class m200926_153000_create_table_courses extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('courses', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->unique()->notNull(),
            'shortDescription' => $this->string(),
            'description' => $this->text(),
            'dateFrom' => $this->date(),
            'dateTo' => $this->date(),
            'teacherId' => $this->integer(),
            'subject' => $this->string(),
            'examType' => $this->string(),
            'price' => $this->double()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('courses');
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
