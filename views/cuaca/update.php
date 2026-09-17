<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Cuaca $model */

$this->title = 'Update Cuaca: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cuaca', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cuaca-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
