<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Courses */

$this->title = Yii::t('app', 'Create Courses');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Courses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="courses-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
