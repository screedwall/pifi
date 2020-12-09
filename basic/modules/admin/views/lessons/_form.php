<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
use kartik\file\FileInput;
use yii\helpers\Url;
use wbraganca\dynamicform\DynamicFormWidget;

/* @var $this yii\web\View */
/* @var $model app\models\Lessons */
/* @var $modelsVideo app\models\Videos */
/* @var $form yii\widgets\ActiveForm */

$dynaFormJs = '
    $(".dynamicform_wrapper").on("afterInsert", function(e, item) {
        console.log(item);
    });
';

$this->registerJs($dynaFormJs);

?>

<div class="lessons-form">

    <?php $form = ActiveForm::begin([
        'id' => 'dynamic-form',
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

    <?php DynamicFormWidget::begin([
        'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
        'widgetBody' => '.container-items', // required: css class selector
        'widgetItem' => '.item', // required: css class
        'min' => 0, // 0 or 1 (default 1)
        'insertButton' => '.add-item', // css class
        'deleteButton' => '.remove-item', // css class
        'model' => $modelsVideo[0],
        'formId' => 'dynamic-form',
        'formFields' => [
            'url',
        ],
    ]); ?>
        <div class="panel panel-default">
            <div class="panel-heading"><h4><i class="glyphicon glyphicon-facetime-video"></i> Видео к уроку</h4>
                <button type="button" class="pull-right add-item btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                <div class="container-items"><!-- widgetContainer -->
                    <?php foreach ($modelsVideo as $i => $modelVideo): ?>
                        <div class="item panel panel-default"><!-- widgetBody -->
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left">Видео</h3>
                                <div class="pull-right">
                                    <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="panel-body">
                                <?php
                                // necessary for update action.
                                if (! $modelVideo->isNewRecord) {
                                    echo Html::activeHiddenInput($modelVideo, "[{$i}]id");
                                }
                                ?>

                                <?= $form->field($modelVideo, "[{$i}]url")->textInput(['maxlength' => true]) ?>

                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    <?php DynamicFormWidget::end(); ?>

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
