<?php

use app\models\Pelabuhan;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\PelabuhanSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Pelabuhan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pelabuhan-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Pelabuhan', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager' => [
            'options' => ['class' => 'pagination pagination-sm justify-content-left my-3'],
            'linkContainerOptions' => ['class' => 'page-item'],
            'linkOptions' => ['class' => 'page-link'],
            'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'],
            'prevPageLabel' => '&laquo; Prev',
            'nextPageLabel' => 'Next &raquo;',
            'firstPageLabel' => 'First',
            'lastPageLabel' => 'Last',
            'maxButtonCount' => 5,
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'code',
            'name',
            'province',
            'created_at',
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Pelabuhan $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>