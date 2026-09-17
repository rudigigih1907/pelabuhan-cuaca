<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Cuaca $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="cuaca-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'code_pelabuhan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'issued')->textInput() ?>

    <?= $form->field($model, 'valid_from')->textInput() ?>

    <?= $form->field($model, 'valid_to')->textInput() ?>

    <?= $form->field($model, 'time')->textInput() ?>

    <?= $form->field($model, 'weather')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'visibility')->textInput() ?>

    <?= $form->field($model, 'temp_avg')->textInput() ?>

    <?= $form->field($model, 'rh_avg')->textInput() ?>

    <?= $form->field($model, 'wind_from')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'wind_speed')->textInput() ?>

    <?= $form->field($model, 'wind_gust')->textInput() ?>

    <?= $form->field($model, 'wave_cat')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'wave_height')->textInput() ?>

    <?= $form->field($model, 'current_to')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'current_speed')->textInput() ?>

    <?= $form->field($model, 'tides')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>