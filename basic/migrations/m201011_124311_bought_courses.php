<?php

use yii\db\Migration;

/**
 * Class m201011_124311_bought_courses
 */
class m201011_124311_bought_courses extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('bought_courses', [
            'id' => $this->primaryKey(),
            'userId' => $this->integer()->notNull(),
            'courseId' => $this->integer()->notNull(),
            'monthId' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-bought_courses-users',
            'bought_courses',
            'userId',
            'users',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-bought_courses-courses',
            'bought_courses',
            'courseId',
            'courses',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-bought_courses-months',
            'bought_courses',
            'monthId',
            'months',
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
            'fk-bought_courses-users',
            'bought_courses'
        );

        $this->dropForeignKey(
            'fk-bought_courses-courses',
            'bought_courses'
        );

        $this->dropForeignKey(
            'fk-bought_courses-months',
            'bought_courses'
        );

        $this->dropTable('bought_courses');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201011_124311_bought_courses cannot be reverted.\n";

        return false;
    }
    */
}
