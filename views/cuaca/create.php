<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Cuaca $model */

$this->title = 'Create Cuaca';
$this->params['breadcrumbs'][] = ['label' => 'Cuaca', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cuaca-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
