<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use GuzzleHttp\Client;

class PelabuhanController extends Controller
{
    public function actionIndex()
    {
        $apiUrl = $_ENV['BMKG_PELABUHAN_META_URL'] ?? 'https://maritim.bmkg.go.id/marine2026-data/meta/pelabuhan.json';
        
        $pelabuhanList = [];
        $errorMessage = null;

        try {
            $client = new Client([
                'timeout' => 10,
                'verify' => false
            ]);
            
            $response = $client->request('GET', $apiUrl);
            $data = json_decode($response->getBody()->getContents(), true);

            if (is_array($data)) {
                $items = isset($data['features']) ? $data['features'] : (isset($data['data']) ? $data['data'] : $data);

                foreach ($items as $item) {
                    $props = $item['properties'] ?? $item;

                    $pelabuhanList[] = [
                        'code' => $props['code'] ?? $props['id'] ?? $props['pelabuhan_id'] ?? '-',
                        'name' => $props['name'] ?? $props['nama'] ?? $props['pelabuhan_nama'] ?? '-',
                        'province' => $props['province'] ?? $props['provinsi'] ?? $props['provinsi'] ?? '-'
                    ];
                }
            }
        } catch (\Exception $e) {
            $errorMessage = "Gagal mengambil data dari BMKG: " . $e->getMessage();
        }

        // Ambil query parameter
        $searchCode = trim(Yii::$app->request->get('search_code', ''));
        $searchName = trim(Yii::$app->request->get('search_name', ''));
        $searchProvince = trim(Yii::$app->request->get('search_province', ''));

        // Filter array data
        if ($searchCode !== '' || $searchName !== '' || $searchProvince !== '') {
            $pelabuhanList = array_filter($pelabuhanList, function ($item) use ($searchCode, $searchName, $searchProvince) {
                $matchCode = ($searchCode === '') || (stripos($item['code'], $searchCode) !== false);
                $matchName = ($searchName === '') || (stripos($item['name'], $searchName) !== false);
                $matchProvince = ($searchProvince === '') || (stripos($item['province'], $searchProvince) !== false);

                return $matchCode && $matchName && $matchProvince;
            });
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => array_values($pelabuhanList),
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'attributes' => ['code', 'name', 'perairan'],
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'errorMessage' => $errorMessage,
            'searchParams' => [
                'search_code' => $searchCode,
                'search_name' => $searchName,
                'search_province' => $searchProvince,
            ],
        ]);
    }
}