<?php

use yii\db\Migration;

/**
 * Class m201123_113214_create_table_users_stream
 */
class m201123_113214_create_table_users_stream extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('users_stream', [
            'id' => $this->primaryKey(),
            'userId' => $this->integer()->notNull(),
            'courseId' => $this->integer()->notNull(),
            'monthId' => $this->integer()->notNull(),
            'type' => $this->string()->notNull(),
            'boughtId' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk_stream_users',
            'users_stream',
            'userId',
            'users',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_stream_courses',
            'users_stream',
            'courseId',
            'courses',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_stream_months',
            'users_stream',
            'monthId',
            'months',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_stream_bought',
            'users_stream',
            'boughtId',
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
            'fk_stream_users',
            'users_stream'
        );

        $this->dropForeignKey(
            'fk_stream_courses',
            'users_stream'
        );

        $this->dropForeignKey(
            'fk_stream_months',
            'users_stream'
        );

        $this->dropTable('users_stream');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201123_113214_create_table_users_stream cannot be reverted.\n";

        return false;
    }
    */
}
