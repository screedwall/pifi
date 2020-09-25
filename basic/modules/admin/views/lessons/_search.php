<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LessonsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lessons-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'shortDescription') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'video') ?>

    <?php // echo $form->field($model, 'lessonDate') ?>

    <?php // echo $form->field($model, 'homeworkDate') ?>

    <?php // echo $form->field($model, 'month') ?>

    <?php // echo $form->field($model, 'course') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Поиск'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Сбросить'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
