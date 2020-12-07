<?php

use yii\db\Migration;

/**
 * Class m201206_204134_create_table_videos
 */
class m201206_204134_create_table_videos extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('videos', [
            'id' => $this->primaryKey(),
            'lessonId' => $this->integer(),
            'video' => $this->string(),
        ]);

        $this->addForeignKey(
            'fk_videos_lessons',
            'videos',
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
        $this->dropTable('videos');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201206_204134_create_table_videos cannot be reverted.\n";

        return false;
    }
    */
}
