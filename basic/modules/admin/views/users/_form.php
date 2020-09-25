<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $form yii\widgets\ActiveForm */
/* @var $roles app\models\Users */
$model->password = '';
?>

<div class="users-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'login')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vk')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'role')->dropDownList($roles) ?>

    <?= $form->field($model, 'teacherId')->dropDownList(ArrayHelper::map(\app\models\Teachers::find()->all(), 'id', 'name'), array('prompt' => '')) ?>

    <?php
        $months = \app\models\Months::find()->all();
        foreach ($months as $month) {
            $month->name = $month->course->name." ".$month->name;
        }
    ?>

    <?= Select2::widget([
        'name' => 'months',
        'value' => ArrayHelper::map($model->months, 'id', 'id'),
        'data' => ArrayHelper::map($months, 'id', 'name'),
        'options' => [
            'placeholder' => 'Подберите пользователей...',
            'multiple' => true
        ],
    ]) ?>
    <br>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>



    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
