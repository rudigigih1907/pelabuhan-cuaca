<?php

namespace app\controllers;

use app\models\Cuaca;
use app\models\CuacaSearch;
use app\models\Pelabuhan;
use app\services\BmkgService;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * CuacaController implements the CRUD actions for Cuaca model.
 */
class CuacaController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Cuaca models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CuacaSearch();
        $queryParams = Yii::$app->request->queryParams;

        // Ambil parameter filter dari request GET
        $selectedProvince = Yii::$app->request->get('province');
        $selectedCode = Yii::$app->request->get('code');

        // Jika pelabuhan dipilih via dropdown top filter, set otomatis ke search model
        if (!empty($selectedCode)) {
            // Pastikan array structure sesuai dengan form searchModel
            if (!isset($queryParams['CuacaSearch'])) {
                $queryParams['CuacaSearch'] = [];
            }
            $queryParams['CuacaSearch']['code_pelabuhan'] = $selectedCode;
        }

        $dataProvider = $searchModel->search($queryParams);

        // Ambil daftar provinsi unik dari tabel pelabuhan
        $listProvinsi = ArrayHelper::map(
            Pelabuhan::find()
                ->select(['province'])
                ->distinct()
                ->where(['not', ['province' => null]])
                ->andWhere(['!=', 'province', ''])
                ->orderBy(['province' => SORT_ASC])
                ->asArray()
                ->all(),
            'province',
            'province'
        );

        // Ambil daftar pelabuhan berdasarkan provinsi yang dipilih
        $listPelabuhan = [];
        if (!empty($selectedProvince)) {
            $listPelabuhan = ArrayHelper::map(
                Pelabuhan::find()
                    ->where(['province' => $selectedProvince])
                    ->orderBy(['name' => SORT_ASC])
                    ->all(),
                'code',
                function ($model) {
                    return "{$model->code} - {$model->name}";
                }
            );
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'listProvinsi' => $listProvinsi,
            'listPelabuhan' => $listPelabuhan,
            'selectedProvince' => $selectedProvince,
            'selectedCode' => $selectedCode,
        ]);
    }

    /**
     * Displays a single Cuaca model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Cuaca model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Cuaca();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Cuaca model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Cuaca model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Cuaca model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Cuaca the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cuaca::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Action untuk menarik data cuaca dari API BMKG
     */
    public function actionSyncBmkg()
    {
        $code = Yii::$app->request->get('code');
        $selectedProvince = Yii::$app->request->get('province');

        if (empty($code)) {
            Yii::$app->session->setFlash('error', 'Silakan pilih pelabuhan terlebih dahulu.');
            return $this->redirect(['index', 'province' => $selectedProvince]);
        }

        $result = BmkgService::syncCuacaPelabuhan($code);

        if ($result['success']) {
            Yii::$app->session->setFlash('success', $result['message']);
        } else {
            Yii::$app->session->setFlash('error', $result['message']);
        }

        return $this->redirect(['index', 'province' => $selectedProvince, 'code' => $code]);
    }
}
