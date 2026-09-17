<?php

namespace app\services;

use app\models\Cuaca;
use Yii;
use yii\httpclient\Client;

class BmkgService
{
    /**
     * Memformat tanggal 'YYYY-MM-DD HH:MM UTC' menjadi 'YYYY-MM-DD HH:MM:SS'
     */
    private static function parseDateTime(?string $dateStr): ?string
    {
        if (empty($dateStr)) {
            return null;
        }
        $cleanStr = trim(str_replace('UTC', '', $dateStr));
        $date = \DateTime::createFromFormat('Y-m-d H:i', $cleanStr);
        return $date ? $date->format('Y-m-d H:i:s') : null;
    }

    /**
     * Menarik data dari API BMKG berdasarkan kode pelabuhan dan menyimpan ke database.
     * 
     * @param string $code Kode pelabuhan (contoh: 'AA001')
     * @return array ['success' => bool, 'message' => string, 'count' => int]
     */
    public static function syncCuacaPelabuhan(string $code): array
    {
        $url = "https://maritim.bmkg.go.id/marine2026-data/pelabuhan/{$code}.json";

        $client = new Client();
        try {
            $response = $client->createRequest()
                ->setMethod('GET')
                ->setUrl($url)
                ->send();

            if (!$response->isOk) {
                return [
                    'success' => false,
                    'message' => "Gagal mengambil data dari BMKG. HTTP Status: {$response->statusCode}",
                    'count' => 0
                ];
            }

            $data = $response->data;
            if (empty($data) || !isset($data['code'])) {
                return [
                    'success' => false,
                    'message' => 'Format response JSON tidak valid.',
                    'count' => 0
                ];
            }

            $codePelabuhan = $data['code'];
            $issued = self::parseDateTime($data['issued'] ?? null);
            $validFrom = self::parseDateTime($data['valid_from'] ?? null);
            $validTo = self::parseDateTime($data['valid_to'] ?? null);

            // Gabungkan forecast_day1 dan forecast_day2-4
            $forecasts = array_merge(
                $data['forecast_day1'] ?? [],
                $data['forecast_day2-4'] ?? []
            );

            $savedCount = 0;

            foreach ($forecasts as $item) {
                $timeFormatted = self::parseDateTime($item['time'] ?? null);
                if (!$timeFormatted) {
                    continue;
                }

                // Cari data berdasarkan unique index (code_pelabuhan + time)
                $model = Cuaca::findOne([
                    'code_pelabuhan' => $codePelabuhan,
                    'time' => $timeFormatted,
                ]);

                if (!$model) {
                    $model = new Cuaca();
                    $model->code_pelabuhan = $codePelabuhan;
                    $model->time = $timeFormatted;
                }

                $model->issued = $issued;
                $model->valid_from = $validFrom;
                $model->valid_to = $validTo;

                $model->weather = $item['weather'] ?? null;
                $model->visibility = isset($item['visibility']) ? (int)$item['visibility'] : null;
                $model->temp_avg = isset($item['temp_avg']) ? (int)$item['temp_avg'] : null;
                $model->rh_avg = isset($item['rh_avg']) ? (int)$item['rh_avg'] : null;
                $model->wind_from = $item['wind_from'] ?? null;
                $model->wind_speed = isset($item['wind_speed']) ? (int)$item['wind_speed'] : null;
                $model->wind_gust = isset($item['wind_gust']) ? (int)$item['wind_gust'] : null;
                $model->wave_cat = $item['wave_cat'] ?? null;
                $model->wave_height = isset($item['wave_height']) ? (float)$item['wave_height'] : null;
                $model->current_to = $item['current_to'] ?? null;
                $model->current_speed = isset($item['current_speed']) ? (float)$item['current_speed'] : null;
                $model->tides = isset($item['tides']) ? (float)$item['tides'] : null;

                if ($model->save()) {
                    $savedCount++;
                }
            }

            return [
                'success' => true,
                'message' => "Berhasil menyinkronkan {$savedCount} data cuaca untuk Pelabuhan {$codePelabuhan}.",
                'count' => $savedCount
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'count' => 0
            ];
        }
    }
}
