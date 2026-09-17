<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cuaca_pelabuhan}}`.
 */
class m260916_070257_create_cuaca_pelabuhan_table extends Migration
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
        $this->createTable('{{%cuaca_pelabuhan}}', [
            'id' => $this->primaryKey(),
            'code_pelabuhan' => $this->string(5)->notNull(),
            'issued' => $this->dateTime()->notNull(),
            'valid_from' => $this->dateTime()->notNull(),
            'valid_to' => $this->dateTime()->notNull(),
            'time' => $this->dateTime()->notNull(),
            'weather' => $this->string(50)->null(),
            'visibility' => $this->integer()->null()->comment('Jarak pandang (km)'),
            'temp_avg' => $this->integer()->null()->comment('Suhu rata-rata (°C)'),
            'rh_avg' => $this->integer()->null()->comment('Kelembaban udara (%)'),
            'wind_from' => $this->string(20)->null(),
            'wind_speed' => $this->integer()->null()->comment('Kecepatan angin (knot)'),
            'wind_gust' => $this->integer()->null()->comment('Hembusan angin maksimum'),
            'wave_cat' => $this->string(50)->null(),
            'wave_height' => $this->float()->null()->comment('Tinggi gelombang (meter)'),
            'current_to' => $this->string(50)->null(),
            'current_speed' => $this->float()->null()->comment('Kecepatan arus (knot)'),
            'tides' => $this->float()->null()->comment('Pasang surut (meter)'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

        // Buat index & Foreign Key mengacu pada kolom 'code' di tabel 'pelabuhan'
        $this->createIndex(
            'idx-cuaca_pelabuhan-code-time',
            '{{%cuaca_pelabuhan}}',
            ['code_pelabuhan', 'time'],
        );

        $this->addForeignKey(
            'fk-cuaca_pelabuhan-pelabuhan_code_pelabuhan',
            '{{%cuaca_pelabuhan}}',
            'code_pelabuhan',
            '{{%pelabuhan}}',
            'code',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-cuaca_pelabuhan-pelabuhan_code_pelabuhan', '{{%cuaca_pelabuhan}}');
        $this->dropIndex('idx-cuaca_pelabuhan-code-time', '{{%cuaca_pelabuhan}}');
        $this->dropTable('{{%cuaca_pelabuhan}}');
    }
}
