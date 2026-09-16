<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Pelabuhan $model */

$this->title = 'Create Pelabuhan';
$this->params['breadcrumbs'][] = ['label' => 'Pelabuhan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pelabuhan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>