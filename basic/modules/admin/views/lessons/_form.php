<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Lessons */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="lessons-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'shortDescription')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'video')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lessonDate')->widget(DateTimePicker::class, [
        'options' => ['autocomplete' => 'off'],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'dd.mm.yyyy HH:ii'
        ]])?>

    <?= $form->field($model, 'homeworkDate')->widget(DateTimePicker::class, [
        'options' => ['autocomplete' => 'off'],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'dd.mm.yyyy HH:ii'
        ]])?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
