<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CoursesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="courses-search">

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

    <?= $form->field($model, 'dateFrom') ?>

    <?php // echo $form->field($model, 'dateTo') ?>

    <?php // echo $form->field($model, 'teacher') ?>

    <?php // echo $form->field($model, 'subject') ?>

    <?php // echo $form->field($model, 'examType') ?>

    <?php // echo $form->field($model, 'price') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Поиск'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Сбросить'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
