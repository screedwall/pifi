<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Months */
/* @var $courseId app\models\Months */

$this->title = Yii::t('app', 'Create Months');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Courses'), 'url' => ['courses/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', $model->course->name), 'url' => ['courses/update', 'id' => $courseId, '#' => 'months']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="months-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model
    ]) ?>

</div>
