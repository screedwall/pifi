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

    <?= $form->field($model, 'price') ?>

    <?php

        if(!isset($new))
            if(!$model->course->isSpec)
            {
                echo $form->field($model, 'priceShort');
                echo $form->field($model, 'priceLong');
            }

    ?>

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

    <div class="form-group">
        <?= Html::label('Подарочные месяцы', 'gifts[]') ?>

        <?php
            if(isset($new))
                $months = \app\models\Months::find()->all();
            else
                $months = \app\models\Months::find()->where(['<>', 'id', $model->id])->all();

            foreach ($months as $month) {
                $month->name = $month->course->name." ".$month->name;
                $month->courseId = $month->course->name;
            }
        ?>

        <?= Select2::widget([
            'name' => 'gifts',
            'value' => ArrayHelper::map($model->gifts, 'id', 'giftId'),
            'data' => ArrayHelper::map($months, 'id', 'name', 'courseId'),
            'options' => [
                'placeholder' => 'Выберите курсы...',
                'multiple' => true
            ],
        ]) ?>
    </div>

    <div class="form-group">
        <?= Html::label('Пользователи месяца', 'users[]') ?>
        <?= Select2::widget([
            'name' => 'users',
            'value' => ArrayHelper::map($model->users, 'id', 'id'),
            'data' => ArrayHelper::map($users, 'id', 'name'),
            'options' => [
                'placeholder' => 'Подберите пользователей...',
                'multiple' => true
            ],
        ]) ?>
    </div>
    <br>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>