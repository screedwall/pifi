<?php

use yii\db\Migration;

/**
 * Class m201012_153114_init_rbac
 */
class m201012_153114_init_rbac extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;
        $admin = $auth->createRole('admin');
        $admin->description = 'Администратор';
        $auth->add($admin);

        $teacher = $auth->createRole('teacher');
        $teacher->description = 'Учитель';
        $auth->add($teacher);

        $user = $auth->createRole('user');
        $user->description = 'Пользователь';
        $auth->add($user);

        $banned = $auth->createRole('banned');
        $banned->description = 'Заблокированный пользователь';
        $auth->add($banned);

        $auth->assign($admin, 1);
        $auth->assign($teacher, 2);
        $auth->assign($user, 3);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;

        $auth->removeAll();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201012_153114_init_rbac cannot be reverted.\n";

        return false;
    }
    */
}
