<?php

namespace app\commands;

use app\services\BmkgService;
use yii\console\Controller;
use yii\console\ExitCode;

class BmkgController extends Controller
{
    /**
     * Menarik data cuaca untuk pelabuhan Jakarta (XJ001 sampai XJ014)
     */
    public function actionSyncJakarta()
    {
        $start = 1;
        $end = 14;

        $this->stdout("--- Mensejajarkan Data Cuaca Pelabuhan Jakarta ---\n");

        for ($i = $start; $i <= $end; $i++) {
            $code = sprintf('XJ%03d', $i);
            $this->stdout("Proses kode: {$code}... ");

            $result = BmkgService::syncCuacaPelabuhan($code);

            if ($result['success']) {
                $this->stdout("[OK] {$result['message']}\n");
            } else {
                $this->stderr("[ERROR] {$result['message']}\n");
            }
        }

        $this->stdout("Selesai.\n");
        return ExitCode::OK;
    }
}
