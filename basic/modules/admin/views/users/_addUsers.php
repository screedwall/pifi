<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use app\models\BoughtCourses;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $form yii\widgets\ActiveForm */
/* @var $id int*/

    if(count(BoughtCourses::findAll(['mounth' => $id])) > 0)
    {

    }else{
        $model = new BoughtCourses();
    }

?>

<div class="users-form">

    <?php $form = ActiveForm::begin(); ?>
<!--    --><?//= $form->field($model, 'user')->widget(Select2::class, [
//        'name' => 'users',
//        'data' => \app\controllers\AppController::getRoles(),
//        'options' => [
//            'multiple' => true
//        ],
//    ]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
