<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pelabuhan}}`.
 */
class m260916_031803_create_pelabuhan_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // Menggunakan engine InnoDB dan charset utf8mb4
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%pelabuhan}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string(50)->notNull()->unique(),
            'name' => $this->string(255)->notNull(),
            'province' => $this->string(255)->null(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $tableOptions);

        $this->createIndex('idx_code_name_province', '{{%pelabuhan}}', ['code', 'name', 'province']);
        $this->createIndex('uk_code', '{{%pelabuhan}}', 'code', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('uk_code', '{{%pelabuhan}}');
        $this->dropIndex('idx_code_name_province', '{{%pelabuhan}}');
        $this->dropTable('{{%pelabuhan}}');
    }
}
