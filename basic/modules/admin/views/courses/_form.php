<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use \app\controllers\AppController;
use kartik\file\FileInput;
use kartik\daterange\DateRangePicker;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Courses */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="courses-form">

    <?php $form = ActiveForm::begin([
            'enableClientValidation' => false,
            'options' => ['enctype' => 'multipart/form-data',
            ]]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'shortDescription')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'isVisible')->checkbox() ?>

    <?= $form->field($model, 'isSpec')->checkbox() ?>

    <?= $form->field($model, 'description')->widget(vova07\imperavi\Widget::class, [
        'settings' => [
            'minHeight' => 200,
            'plugins' => [
                'fullscreen',
            ],
        ],
    ]) ?>

    <?= Yii::$app->user->identity->isAdmin() ? $form->field($model, 'teacherId')->dropDownList(ArrayHelper::map(\app\models\Teachers::find()->all(), 'id', 'name')) : '' ?>

    <?= $form->field($model, 'subject')->dropDownList(AppController::getSubjects()) ?>

    <?= $form->field($model, 'examType')->dropDownList(AppController::getExams()) ?>

    <?= $form->field($model, 'thumbnail')->widget(budyaga\cropper\Widget::class, [
        'uploadUrl' => \yii\helpers\Url::toRoute('courses/uploadPhoto'),
        'width' => '500',
        'height' => '500',
    ])?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
