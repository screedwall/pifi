<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Coupons */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coupons-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
        $months = \app\models\Months::find()->with('course')->all();
        foreach ($months as $month) {
            $month->name = $month->course->name." ".$month->name;
            $month->courseId = $month->course->name;
        }
    ?>

    <?= $form->field($model, 'monthId')->widget(Select2::class, [
        'data' => ArrayHelper::map($months, 'id', 'name', 'courseId'),
        'options' => [
            'placeholder' => 'Выберите курсы...',
            'multiple' => false
        ],
    ]); ?>

    <?= $form->field($model, 'count')->textInput() ?>

    <?= $form->field($model, 'discount')->textInput() ?>

    <?= $form->field($model, 'code')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
