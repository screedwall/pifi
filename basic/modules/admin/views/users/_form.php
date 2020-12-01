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
        <?= Html::label('Дата регистрации', 'register-date') ?>
        <?= Html::textInput('register-date', $model->createdAt, [
            'class' => 'form-control',
            'disabled' => true,
        ]) ?>
    </div>

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
        $months = \app\models\Months::find()->with('course')->all();
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
                'multiple' => true,
                'options' => [],
            ],
        ]) ?>
    </div>

    <?php
        $disableSel2Group = <<< JS
        function disableSel2Group(evt, target, disabled) {
            
            var group = evt.params.args.data.element.parentElement.children;
            
            $.each(group, function(idx, item) {
              if(item != evt.params.args.data.element)
              {
                item.disabled = disabled;
              }
        })
    }
JS;
        $this->registerJs($disableSel2Group);

        $streams = [];
        foreach ($model->streams as $stream)
            array_push($streams, $stream->month);

        $disabledStreams = [];
        $disabledGroups = [];
        $enabledElements = [];
        foreach ($months as $month)
        {
            foreach ($model->streams as $stream)
            {
                if($stream->month->id == $month->id)
                {
                    array_push($disabledGroups, $month->courseId);
                    array_push($enabledElements, $month->id);
                }
            }
        }

        foreach ($months as $month)
        {
            if(in_array($month->courseId, $disabledGroups) && !in_array($month->id, $enabledElements))
                $disabledStreams[$month->id] = ['disabled' => true];
        }
    ?>

    <div class="form-group">
        <?= Html::label('Потоки пользователя', 'streams[]') ?>
        <?= Select2::widget([
            'name' => 'streams',
            'value' => ArrayHelper::map($streams, 'id', 'id'),
            'data' => ArrayHelper::map($months, 'id', 'name', 'courseId'),
            'options' => [
                'placeholder' => 'Выберите курсы...',
                'multiple' => true,
                'options' => $disabledStreams,
            ],
            'pluginEvents' => [
                "select2:selecting" => "function(evt) { disableSel2Group(evt, this, true); }",
                "select2:unselecting" => "function(evt) { disableSel2Group(evt, this, false); }",
            ]
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
