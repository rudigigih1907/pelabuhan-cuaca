<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\CuacaSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="cuaca-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'code_pelabuhan') ?>

    <?= $form->field($model, 'issued') ?>

    <?= $form->field($model, 'valid_from') ?>

    <?= $form->field($model, 'valid_to') ?>

    <?php // echo $form->field($model, 'time') ?>

    <?php // echo $form->field($model, 'weather') ?>

    <?php // echo $form->field($model, 'visibility') ?>

    <?php // echo $form->field($model, 'temp_avg') ?>

    <?php // echo $form->field($model, 'rh_avg') ?>

    <?php // echo $form->field($model, 'wind_from') ?>

    <?php // echo $form->field($model, 'wind_speed') ?>

    <?php // echo $form->field($model, 'wind_gust') ?>

    <?php // echo $form->field($model, 'wave_cat') ?>

    <?php // echo $form->field($model, 'wave_height') ?>

    <?php // echo $form->field($model, 'current_to') ?>

    <?php // echo $form->field($model, 'current_speed') ?>

    <?php // echo $form->field($model, 'tides') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
