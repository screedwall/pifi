<?php

use yii\db\Migration;

/**
 * Class m201108_113143_create_table_lesson_attachments
 */
class m201108_113143_create_table_lesson_attachments extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('lesson_attachments', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'path' => $this->string()->notNull(),
            'lessonId' => $this->integer()->notNull(),
        ]);


        $this->addForeignKey(
            'fk_lessons_lessonAttachments',
            'lesson_attachments',
            'lessonId',
            'lessons',
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
            'fk_lessons_lessonAttachments',
            'lesson_attachments'
        );

        $this->dropTable('lesson_attachments');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201108_113143_create_table_lesson_attachments cannot be reverted.\n";

        return false;
    }
    */
}
