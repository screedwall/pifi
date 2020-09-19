<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\mounths */
/* @var $form yii\widgets\ActiveForm */
/* @var $courseId app\models\mounths */

?>

<div class="mounths-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dateFrom')->widget(DatePicker::class, [
        'options' => ['autocomplete' => 'off'],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'dd.mm.yyyy'
        ]])?>

    <?= $form->field($model, 'dateTo')->widget(DatePicker::class, [
        'options' => ['autocomplete' => 'off'],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'dd.mm.yyyy'
        ]])?>

    <?= $form->field($model, 'course')->hiddenInput(['value'=> $courseId])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>