<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\mounths */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mounths-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'dateFrom', ['inputOptions' => ['autocomplete' => 'off']])->widget(DatePicker::class, ['dateFormat' => 'dd.MM.yyyy'])->textInput() ?>

    <?= $form->field($model, 'dateTo', ['inputOptions' => ['autocomplete' => 'off']])->widget(DatePicker::class, ['dateFormat' => 'dd.MM.yyyy'])->textInput() ?>

    <?= $form->field($model, 'course')->hiddenInput(['value'=> Yii::$app->request->get('courseId')])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>