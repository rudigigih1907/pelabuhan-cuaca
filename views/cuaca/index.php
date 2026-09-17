<?php

use app\models\Cuaca;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\CuacaSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var array $listProvinsi */
/** @var array $listPelabuhan */
/** @var string|null $selectedProvince */
/** @var string|null $selectedCode */

$this->title = 'Cuaca Pelabuhan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cuaca-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(['id' => 'cuaca-pjax-container', 'enablePushState' => true]); ?>

    <div class="card mb-4">
        <div class="card-body">
            <?= Html::beginForm(['cuaca/index'], 'get', ['data-pjax' => true, 'id' => 'form-filter-pelabuhan']) ?>
            <div class="row g-3 align-items-end">

                <!-- Dropdown Provinsi -->
                <div class="col-md-4">
                    <label class="form-label font-weight-bold fw-bold">Provinsi</label>
                    <?= Html::dropDownList('province', $selectedProvince, $listProvinsi ?? [], [
                        'prompt' => '-- Pilih Provinsi --',
                        'class' => 'form-select form-control',
                        'onchange' => '$(this).closest("form").submit();'
                    ]) ?>
                </div>

                <!-- Dropdown Pelabuhan -->
                <?php if (!empty($selectedProvince)): ?>
                    <div class="col-md-4">
                        <label class="form-label font-weight-bold fw-bold">Pelabuhan</label>
                        <?= Html::dropDownList('code', $selectedCode, $listPelabuhan ?? [], [
                            'prompt' => '-- Semua Pelabuhan di Provinsi Ini --',
                            'class' => 'form-select form-control',
                            'onchange' => '$(this).closest("form").submit();'
                        ]) ?>
                    </div>
                <?php endif; ?>

                <!-- Tombol Tarik Data API BMKG (Hanya jika Pelabuhan dipilih) -->
                <?php if (!empty($selectedCode)): ?>
                    <div class="col-md-4">
                        <?= Html::a(
                            '<i class="bi bi-cloud-download"></i> Tarik Data API BMKG',
                            ['sync-bmkg', 'code' => $selectedCode, 'province' => $selectedProvince],
                            [
                                'class' => 'btn btn-primary w-100',
                                'data-pjax' => '0',
                            ]
                        ) ?>
                    </div>
                <?php endif; ?>

            </div>
            <?= Html::endForm() ?>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{summary}\n{items}\n<div class='d-flex justify-content-left'>{pager}</div>",
        'pager' => [
            'class' => \yii\bootstrap5\LinkPager::class,
            'firstPageLabel' => 'Awal',
            'lastPageLabel' => 'Akhir',
            'prevPageLabel' => '&laquo;',
            'nextPageLabel' => '&raquo;',
            'maxButtonCount' => 5,
            'options' => ['class' => 'pagination justify-content-center my-3'],
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'nama_pelabuhan',
                'label' => 'Nama Pelabuhan',
                'value' => function ($model) {
                    return $model->pelabuhan ? $model->pelabuhan->name : '-';
                },
            ],
            'time',
            'weather',
            [
                'attribute' => 'temp_avg',
                'value' => function ($model) {
                    return $model->temp_avg !== null ? $model->temp_avg . ' °C' : '-';
                }
            ],
            [
                'attribute' => 'rh_avg',
                'value' => function ($model) {
                    return $model->rh_avg !== null ? $model->rh_avg . ' %' : '-';
                }
            ],
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Cuaca $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>