<?php

use yii\db\Migration;
use app\models\Months;

/**
 * Class m210228_220104_update_table_months
 */
class m210228_220104_update_table_months extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Months::tableName(), 'priceExtraShort', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Months::tableName(), 'priceExtraShort');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210228_220104_update_table_months cannot be reverted.\n";

        return false;
    }
    */
}
