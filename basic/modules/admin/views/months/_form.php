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
            echo $form->field($model, 'priceExtraShort');
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


    <?php if(!isset($new)): ?>

        <?php if(!$model->course->isSpec): ?>
            <div class="form-group">
                <?= Html::label('Подарки новым пользователям', 'gifts[]') ?>

                <?php
                    $months = \app\models\Months::find()
                                ->where(['courseId' => $model->courseId])
                                ->orWhere(['in', 'courseId', ArrayHelper::getColumn(\app\models\Courses::find()
                                                                                                ->select('id')
                                                                                                ->where(['isSpec' => true])
                                                                                                ->asArray()
                                                                                                ->all(), 'id')])
                                ->with('course')
                                ->all();

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
                <?= Html::label('Подарки за продление', 'extensions[]') ?>

                <?= Select2::widget([
                    'name' => 'extensions',
                    'value' => ArrayHelper::map($model->extensions, 'id', 'giftId'),
                    'data' => ArrayHelper::map($months, 'id', 'name', 'courseId'),
                    'options' => [
                        'placeholder' => 'Выберите курсы...',
                        'multiple' => true
                    ],
                ]) ?>
            </div>

            <div class="form-group">
                <?= Html::label('Подарки за 2х и 3х месячный абонементы', 'shorts[]') ?>

                <?= Select2::widget([
                    'name' => 'shorts',
                    'value' => ArrayHelper::map($model->shorts, 'id', 'giftId'),
                    'data' => ArrayHelper::map($months, 'id', 'name', 'courseId'),
                    'options' => [
                        'placeholder' => 'Выберите курсы...',
                        'multiple' => true
                    ],
                ]) ?>
            </div>

            <div class="form-group">
                <?= Html::label('Подарки за годовой абонемент', 'longs[]') ?>

                <?= Select2::widget([
                    'name' => 'longs',
                    'value' => ArrayHelper::map($model->longs, 'id', 'giftId'),
                    'data' => ArrayHelper::map($months, 'id', 'name', 'courseId'),
                    'options' => [
                        'placeholder' => 'Выберите курсы...',
                        'multiple' => true
                    ],
                ]) ?>
            </div>

        <?php endif; ?>
    <?php endif; ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>