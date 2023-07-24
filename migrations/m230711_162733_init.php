<?php

use yii\db\Migration;

/**
 * Class m230711_162733_init
 */
class m230711_162733_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%rates}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string()->notNull(),
            'rate' => $this->float(4)->notNull(),
            'name' => $this->string()->notNull(),
            'date' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%rates}}');
    }
}
