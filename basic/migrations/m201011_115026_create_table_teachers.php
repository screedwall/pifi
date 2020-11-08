<?php

use yii\db\Migration;

/**
 * Class m201011_115026_createtable_teachers
 */
class m201011_115026_create_table_teachers extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('teachers', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'subject' => $this->string(),
            'description' => $this->text(),
            'contact' => $this->string(),
            'thumbnail' => $this->string(),
            'splash' => $this->string(),
        ]);

        $this->insert('teachers', [
            'name' => 'Вася Валерьевич',
            'description' => 'Мастер группа - это платный годовой курс онлайн-подготовки к ЕГЭ, преимуществами которой являются цена и удобство обучения😎',
            'subject' => 'Матан',
            'contact' => 'vk.com/id1',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('teachers');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201011_115026_createtable_teachers cannot be reverted.\n";

        return false;
    }
    */
}
