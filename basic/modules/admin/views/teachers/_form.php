<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\controllers\AppController;

/* @var $this yii\web\View */
/* @var $model app\models\Teachers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="teachers-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'subject')->dropDownList(AppController::getSubjects()) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'contact')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'thumbnail')->widget(budyaga\cropper\Widget::class, [
        'uploadUrl' => \yii\helpers\Url::toRoute('teachers/uploadPhoto'),
        'width' => '500',
        'height' => '500',
    ])?>

    <?= $form->field($model, 'splash')->widget(budyaga\cropper\Widget::class, [
        'uploadUrl' => \yii\helpers\Url::toRoute('teachers/uploadPhoto'),
        'width' => '1200',
        'height' => '500',
    ])?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
