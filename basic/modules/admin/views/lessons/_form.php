<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
use kartik\file\FileInput;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Lessons */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="lessons-form">

    <?php $form = ActiveForm::begin([
        'options'=>['enctype'=>'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>


    <?= $form->field($model, 'description')->widget(vova07\imperavi\Widget::class, [
        'settings' => [
            'minHeight' => 200,
            'plugins' => [
                'fullscreen',
            ],
        ],
    ]) ?>

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

    <?php if(!isset($new)): ?>
    <div class="form-group">
        <?php
            $attachments = \app\models\LessonAttachments::findAll(['lessonId' => $model->id]);
            $preview = [];
            $config = [];
            foreach ($attachments as $attachment)
            {
                array_push($preview, "/".$attachment->path);
                array_push($config, ['caption' => $attachment->name, 'key' => $attachment->id]);
            }
        ?>
        <?= Html::label('Материалы к уроку', 'files[]') ?>
        <?=FileInput::widget([
            'name' => 'files[]',
            'options'=>[
                'multiple' => true,
            ],
            'pluginOptions' => [
                'initialPreview' => $preview,
                'initialPreviewAsData' => true,
                'initialPreviewConfig' => $config,
                'overwriteInitial' => false,
                'uploadUrl' => Url::to(['lesson-attachments/upload']),
                'deleteUrl' => Url::to(['lesson-attachments/delete']),
//                'uploadAsync' => false,
                'uploadExtraData' => [
                    'lessonId' => $model->id,
                ],
//                'deleteExtraData' => [
//                    'lessonId' => $model->id,
//                ],
                'showRemove' => false,
                'showUpload' => true,
                'maxFileCount' => 10,
//                'previewFileType' => 'any'
            ]
        ])?>
    </div>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
