<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use \app\controllers\AppController;

/* @var $this yii\web\View */
/* @var $model app\models\Courses */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="courses-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'shortDescription')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'dateFrom', ['inputOptions' => ['autocomplete' => 'off']])->widget(DatePicker::class, ['dateFormat' => 'dd.MM.yyyy'])->textInput() ?>

    <?= $form->field($model, 'dateTo', ['inputOptions' => ['autocomplete' => 'off']])->widget(DatePicker::class, ['dateFormat' => 'dd.MM.yyyy'])->textInput() ?>

    <?= $form->field($model, 'teacher')->dropDownList(ArrayHelper::map(\app\models\Teachers::find()->all(), 'name', 'name')) ?>

    <?= $form->field($model, 'subject')->dropDownList(AppController::getSubjects()) ?>

    <?= $form->field($model, 'examType')->dropDownList(AppController::getExams()) ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>