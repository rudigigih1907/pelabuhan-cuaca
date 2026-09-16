<?php

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use yii\httpclient\Client;
use app\models\Pelabuhan;

class PelabuhanController extends Controller
{
    /**
     * Memuat data pelabuhan dari BMKG (Format GeoJSON) dan menyimpannya ke database (hanya sekali).
     */
    public function actionSync()
    {
        $url = 'https://maritim.bmkg.go.id/marine2026-data/meta/pelabuhan.json';
        $client = new Client();

        $this->stdout("Mengambil data dari BMKG...\n");

        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl($url)
            ->send();

        if (!$response->isOk) {
            $this->stderr("Gagal mengambil data dari API BMKG.\n");
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $data = $response->data;

        // Validasi struktur GeoJSON (memastikan kunci 'features' ada)
        if (empty($data) || !isset($data['features']) || !is_array($data['features'])) {
            $this->stderr("Format data GeoJSON tidak valid atau kosong.\n");
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $insertedCount = 0;
        $skippedCount = 0;

        foreach ($data['features'] as $feature) {
            // Ambil data dari elemen 'properties'
            $properties = $feature['properties'] ?? [];

            $code = $properties['code'] ?? null;
            $name = $properties['name'] ?? null;
            $province = $properties['province'] ?? null;

            // Lewati jika kode atau nama tidak lengkap
            if (!$code || !$name) {
                continue;
            }

            // Cek apakah data sudah ada di database berdasarkan 'code' agar tidak terduplikasi
            $exists = Pelabuhan::find()->where(['code' => $code])->exists();

            if (!$exists) {
                $pelabuhan = new Pelabuhan();
                $pelabuhan->code = $code;
                $pelabuhan->name = $name;
                $pelabuhan->province = $province;

                if ($pelabuhan->save()) {
                    $insertedCount++;
                } else {
                    $this->stderr("Gagal menyimpan data dengan kode: {$code}\n");
                }
            } else {
                $skippedCount++;
            }
        }

        $this->stdout("Proses sinkronisasi selesai.\n");
        $this->stdout("Data baru disimpan      : {$insertedCount}\n");
        $this->stdout("Data dilewati (sudah ada): {$skippedCount}\n");

        return ExitCode::OK;
    }
}
