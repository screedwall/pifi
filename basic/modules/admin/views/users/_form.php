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

    <?= $form->field($model, 'teacher')->dropDownList(ArrayHelper::map(\app\models\Teachers::find()->all(), 'name', 'name'), array('prompt' => '')) ?>

<!--    --><?//= Select2::widget([
//        'name' => 'users',
////        'value' => $users,
//        'data' => ArrayHelper::map(\app\models\Mounths::find()->all(), 'id', 'name'),
//        'options' => [
//            'placeholder' => 'Подберите пользователей...',
//            'multiple' => true
//        ],
//    ]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>



    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$request = \app\models\Mounths::find()->all();
$courses = [];
foreach ($request as $item) {
    print_r ($item->coursee);
    echo "<br>";
}
//print_r($request);
