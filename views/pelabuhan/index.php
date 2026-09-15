<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\base\DynamicModel;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ArrayDataProvider */
/* @var $errorMessage string|null */
/* @var $searchParams array */

$this->title = 'Daftar Pelabuhan BMKG';

$pelabuhanFilterModel = new DynamicModel(['search_code', 'search_name', 'search_province']);
?>

<div class="pelabuhan-index container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><?= Html::encode($this->title) ?></h1>
        <span class="badge bg-primary">Total: <?= $dataProvider->getTotalCount() ?> Pelabuhan</span>
    </div>

    <?php if ($errorMessage): ?>
        <div class="alert alert-danger">
            <?= Html::encode($errorMessage) ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <?php Pjax::begin([
                'id' => 'pelabuhan-pjax',
                'timeout' => 5000,
                'enablePushState' => true,
            ]); ?>

            <form id="filter-form" method="get" action="<?= \yii\helpers\Url::to(['pelabuhan/index']) ?>" data-pjax="1">
                <input type="hidden" name="r" value="pelabuhan/index">

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $pelabuhanFilterModel,
                    'tableOptions' => ['class' => 'table table-striped table-bordered mb-0 align-middle'],
                    'layout' => "<div class='p-3 d-flex justify-content-between align-items-center'>{summary} " .
                        Html::a('Reset Filter', ['pelabuhan/index'], ['class' => 'btn btn-sm btn-outline-secondary', 'data-pjax' => 1]) .
                        "</div>\n<div class='table-responsive'>{items}</div>\n<div class='p-3 d-flex justify-content-end'>{pager}</div>",
                    'pager' => [
                        'class' => 'yii\bootstrap5\LinkPager',
                        'firstPageLabel' => 'First',
                        'lastPageLabel' => 'Last',
                        'prevPageLabel' => '&laquo;',
                        'nextPageLabel' => '&raquo;',
                        'maxButtonCount' => 5,
                    ],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'header' => 'No',
                            'headerOptions' => ['style' => 'width: 70px;', 'class' => 'text-center'],
                            'contentOptions' => ['class' => 'text-center'],
                        ],
                        [
                            'attribute' => 'code',
                            'label' => 'Kode Pelabuhan',
                            'format' => 'raw',
                            'headerOptions' => ['style' => 'width: 200px;'],
                            'filter' => Html::textInput('search_code', $searchParams['search_code'], [
                                'class' => 'form-control form-control-sm filter-input',
                                'placeholder' => 'Cari Kode...'
                            ]),
                            'value' => function ($model) {
                                return '<code>' . Html::encode($model['code']) . '</code>';
                            },
                        ],
                        [
                            'attribute' => 'name',
                            'label' => 'Nama Pelabuhan',
                            'format' => 'raw',
                            'filter' => Html::textInput('search_name', $searchParams['search_name'], [
                                'class' => 'form-control form-control-sm filter-input',
                                'placeholder' => 'Cari Pelabuhan...'
                            ]),
                            'value' => function ($model) {
                                return '<strong>' . Html::encode($model['name']) . '</strong>';
                            },
                        ],
                        [
                            'attribute' => 'province',
                            'label' => 'Provinsi',
                            'format' => 'raw',
                            'filter' => Html::textInput('search_province', $searchParams['search_province'], [
                                'class' => 'form-control form-control-sm filter-input',
                                'placeholder' => 'Cari Provinsi...'
                            ]),
                            'value' => function ($model) {
                                return '<strong>' . Html::encode($model['province']) . '</strong>';
                            },
                        ],
                    ],
                ]); ?>
            </form>

            <?php Pjax::end(); ?>
        </div>
    </div>
</div>

<?php
$js = <<<JS
$(document).on('keypress', '.filter-input', function(e) {
    if (e.which == 13) {
        e.preventDefault();
        $('#filter-form').submit();
    }
});
JS;
$this->registerJs($js);
?>