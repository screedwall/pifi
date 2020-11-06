<?php

use yii\db\Migration;

/**
 * Class m201011_115811_users
 */
class m201011_115811_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'login' => $this->string()->unique()->notNull(),
            'name' => $this->string()->notNull(),
            'email' => $this->string(),
            'vk' => $this->string(),
            'description' => $this->string(),
            'role' => $this->integer(),
            'teacherId' => $this->integer(),
            'authKey' => $this->string(),
            'password' => $this->string(),
            'access_token' => $this->string(),
        ]);

        $this->insert('users', [
            'login' => 'Админ',
            'name' => 'Администратор',
            'email' => 'avtulev@gmail.com',
            'vk' => 'vk.com/id1',
            'description' => 'Ну что-то',
            'role' => 0,
            'authKey' => Yii::$app->getSecurity()->generateRandomString(),
            'password' => Yii::$app->getSecurity()->generatePasswordHash('1'),
        ]);

        $this->insert('users', [
            'login' => 'Вася',
            'name' => 'Вася Валерьевич',
            'email' => 'screedwall@gmail.com',
            'vk' => 'vk.com/id2',
            'description' => 'Ну что-то',
            'role' => 1,
            'teacherId' => '1',
            'authKey' => Yii::$app->getSecurity()->generateRandomString(),
            'password' => Yii::$app->getSecurity()->generatePasswordHash('2'),
        ]);

        $this->insert('users', [
            'login' => 'Ученик',
            'name' => 'Петя',
            'vk' => 'vk.com/id3',
            'description' => 'Ну что-то',
            'role' => 2,
            'authKey' => Yii::$app->getSecurity()->generateRandomString(),
            'password' => Yii::$app->getSecurity()->generatePasswordHash('2'),
        ]);

        $this->addForeignKey(
            'fk_users_teachers',
            'users',
            'teacherId',
            'teachers',
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
            'fk_users_teachers',
            'users'
        );

        $this->dropTable('users');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201011_115811_users cannot be reverted.\n";

        return false;
    }
    */
}
