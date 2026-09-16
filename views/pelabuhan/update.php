<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Pelabuhan $model */

$this->title = 'Update Pelabuhan: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Pelabuhan', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pelabuhan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>