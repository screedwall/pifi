<?php

use app\models\Users;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\models\BoughtCourses;
use kartik\daterange\DateRangePicker;
use yii\web\JsExpression;

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

    <div class="form-group">
        <?= Html::label('Подарочные месяцы', 'gifts[]') ?>

        <?php
            if(isset($new))
                $months = \app\models\Months::find()->with('course')->all();
            else
                $months = \app\models\Months::find()->with('course')->where(['<>', 'id', $model->id])->all();

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

    <?php if(!isset($new)): ?>

        <?php

        $values = [];
        $keys = [];
        foreach ($model->users as $user)
        {
            array_push($values, $user->name.' '.$user->vk);
            array_push($keys, $user->id);
        }

        ?>

        <div class="form-group">
            <?= Html::label('Пользователи месяца', 'users[]') ?>
            <?= Select2::widget([
                'name' => "users[]",
                'initValueText' => $values,
                'value' => $keys,
                'options' => [
                    'placeholder' => 'Подберите пользователей...',
                    'multiple' => true,
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 3,
                    'ajax' => [
                        'url' => \yii\helpers\Url::to(['users/list']),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term}; }'),
                        'delay' => 350,
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(user) { return user.text; }'),
                    'templateSelection' => new JsExpression('function(user) { return user.text; }'),
                ],
            ]) ?>
        </div>
        <br>
    <?php endif; ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>