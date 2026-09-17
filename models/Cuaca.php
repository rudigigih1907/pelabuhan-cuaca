<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cuaca_pelabuhan".
 *
 * @property int $id
 * @property string $code_pelabuhan
 * @property string $issued
 * @property string $valid_from
 * @property string $valid_to
 * @property string $time
 * @property string|null $weather
 * @property int|null $visibility Jarak pandang (km)
 * @property int|null $temp_avg Suhu rata-rata (°C)
 * @property int|null $rh_avg Kelembaban udara (%)
 * @property string|null $wind_from
 * @property int|null $wind_speed Kecepatan angin (knot)
 * @property int|null $wind_gust Hembusan angin maksimum
 * @property string|null $wave_cat
 * @property float|null $wave_height Tinggi gelombang (meter)
 * @property string|null $current_to
 * @property float|null $current_speed Kecepatan arus (knot)
 * @property float|null $tides Pasang surut (meter)
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Pelabuhan $codePelabuhan
 */
class Cuaca extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cuaca_pelabuhan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['weather', 'visibility', 'temp_avg', 'rh_avg', 'wind_from', 'wind_speed', 'wind_gust', 'wave_cat', 'wave_height', 'current_to', 'current_speed', 'tides'], 'default', 'value' => null],
            [['code_pelabuhan', 'issued', 'valid_from', 'valid_to', 'time'], 'required'],
            [['issued', 'valid_from', 'valid_to', 'time', 'created_at', 'updated_at'], 'safe'],
            [['visibility', 'temp_avg', 'rh_avg', 'wind_speed', 'wind_gust'], 'integer'],
            [['wave_height', 'current_speed', 'tides'], 'number'],
            [['code_pelabuhan'], 'string', 'max' => 5],
            [['weather', 'wave_cat', 'current_to'], 'string', 'max' => 50],
            [['wind_from'], 'string', 'max' => 20],
            [['code_pelabuhan'], 'exist', 'skipOnError' => true, 'targetClass' => Pelabuhan::class, 'targetAttribute' => ['code_pelabuhan' => 'code']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code_pelabuhan' => 'Code Pelabuhan',
            'issued' => 'Issued',
            'valid_from' => 'Valid From',
            'valid_to' => 'Valid To',
            'time' => 'Waktu Prakiraan',
            'weather' => 'Cuaca',
            'visibility' => 'Jarak Pandang (km)',
            'temp_avg' => 'Suhu Rata-rata (°C)',
            'rh_avg' => 'Kelembaban (%)',
            'wind_from' => 'Arah Angin',
            'wind_speed' => 'Kecepatan Angin (knot)',
            'wind_gust' => 'Hembusan Angin Maks',
            'wave_cat' => 'Kategori Gelombang',
            'wave_height' => 'Tinggi Gelombang (m)',
            'current_to' => 'Arah Arus',
            'current_speed' => 'Kecepatan Arus (knot)',
            'tides' => 'Pasang Surut (m)',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[CodePelabuhan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPelabuhan()
    {
        return $this->hasOne(Pelabuhan::class, ['code' => 'code_pelabuhan']);
    }

}
