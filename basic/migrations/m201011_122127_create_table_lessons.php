<?php

use yii\db\Migration;

/**
 * Class m201011_122127_createtable_lessons
 */
class m201011_122127_create_table_lessons extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('lessons', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'description' => $this->string(),
            'video' => $this->string(),
            'lessonDate' => $this->dateTime(),
            'homeworkDate' => $this->dateTime(),
            'monthId' => $this->integer()->notNull(),
            'courseId' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk_lessons_months',
            'lessons',
            'monthId',
            'months',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_lessons_course',
            'lessons',
            'courseId',
            'courses',
            'id',
            'CASCADE'
        );

        $this->insert('lessons', [
            'name' => 'Урок 1. Тест №1',
            'description' => 'Д. И. Фонвизин "Недоросль"',
            'video' => 'youtube.com/embed/tgbNymZ7vqY"',
            'lessonDate' => date('Y-m-d H:i'),
            'homeworkDate' => date('Y-m-d H:i'),
            'monthId' => 1,
            'courseId' => 1,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk_lessons_months',
            'lessons'
        );

        $this->dropTable('lessons');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201011_122127_createtable_lessons cannot be reverted.\n";

        return false;
    }
    */
}
