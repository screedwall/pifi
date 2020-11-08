<?php

use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\bootstrap\Html;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $form yii\widgets\ActiveForm */
/* @var $roles app\models\Users */
?>

<div class="users-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php if(isset($new)): ?>

    <?= $form->field($model, 'login')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vk')->textInput(['maxlength' => true]) ?>

    <?php else: ?>

    <div class="form-group">
        <?= Html::label('Логин', 'users-login') ?>
        <?= Html::textInput('users-login', $model->login, [
            'class' => 'form-control',
            'disabled' => true,
        ]) ?>
    </div>

    <div class="form-group">
        <?= Html::label('Ссылка на VK', 'users-vk') ?>
        <?= Html::textInput('users-vk', $model->vk, [
            'class' => 'form-control',
            'disabled' => true,
        ]) ?>
    </div>

    <?php endif; ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['maxlength' => true]) ?>

    <?= $form->field($model, 'role')->dropDownList(\app\controllers\AppController::getRoles()) ?>

    <?= $form->field($model, 'teacherId')->dropDownList(ArrayHelper::map(\app\models\Teachers::find()->all(), 'id', 'name'), array('prompt' => '')) ?>

    <?php
        $months = \app\models\Months::find()->all();
        foreach ($months as $month) {
            $month->name = $month->course->name." ".$month->name;
            $month->courseId = $month->course->name;
        }
    ?>

    <?php if(!isset($new)): ?>
    <div class="form-group">
        <?= Html::label('Курсы пользователя', 'months[]') ?>
        <?= Select2::widget([
            'name' => 'months',
            'value' => ArrayHelper::map($model->months, 'id', 'id'),
            'data' => ArrayHelper::map($months, 'id', 'name', 'courseId'),
            'options' => [
                'placeholder' => 'Выберите курсы...',
                'multiple' => true
            ],
        ]) ?>
    </div>

    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php if(!isset($new)): ?>
        <div class="form-group">
            <?= Html::label('Смена пароля', 'users-password') ?>
            <?php Modal::begin([
                'header' => '<h3>Смена пароля</h3>',
                'toggleButton' => [
                    'label' => 'Сменить пароль',
                    'class' => 'btn btn-primary btn-lg btn-block'
                ],
            ]); ?>

            <?php
                echo Html::beginForm(['change-password', 'id' => $model->id], 'GET');

                echo "<div class=\"form-group\">";
                echo Html::label('Введите пароль', 'password');
                echo Html::textInput('password', '', [
                    'class' => 'form-control',
                    'minlength' => 6
                ]);
                echo "</div>";

                echo "<div class=\"form-group\">";
                echo Html::submitButton('Изменить', ['class' => 'btn btn-success btn-lg btn-block']);
                echo "</div>";

                echo Html::endForm();
            ?>

            <?php Modal::end(); ?>
        </div>
    <?php endif; ?>

</div>
