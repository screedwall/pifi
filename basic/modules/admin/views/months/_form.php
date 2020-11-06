<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\models\BoughtCourses;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Months */
/* @var $form yii\widgets\ActiveForm */
/* @var $courseId app\models\Months */
?>

<div class="months-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= '<div class="form-group field-courses-datetime_range required">'
    .'<label class="control-label has-star" for="courses-datetime_range">Месяц длится</label>'
    .DateRangePicker::widget([
        'model' => $model,
        'name' => 'datetime_range',
        'value' => (isset($model->dateFrom) ? $model->dateFrom.' - '.$model->dateTo : date("d.m.Y").' - '.date("d.m.Y")),
        'startAttribute' => 'Months[dateFrom]',
        'endAttribute' => 'Months[dateTo]',
        'convertFormat'=>true,
        'pluginOptions'=>[
            'locale'=>['format' => 'd.m.Y'],
        ]
    ])
    .'</div>' ?>

    <?php
        $users = \app\models\Users::find()->all();
        foreach ($users as $user) {
            $user->name = $user->name." ".$user->vk;
        }
    ?>

    <?= Select2::widget([
        'name' => 'users',
        'value' => ArrayHelper::map($model->users, 'id', 'id'),
        'data' => ArrayHelper::map($users, 'id', 'name'),
        'options' => [
            'placeholder' => 'Подберите пользователей...',
            'multiple' => true
        ],
    ]) ?>
    <br>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>