<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Lessons */
/* @var $courseId app\models\Lessons */
/* @var $monthId app\models\Lessons */
/* @var $modelsVideo app\models\Videos */

$this->title = Yii::t('app', 'Update Lessons: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Courses'), 'url' => ['courses/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', $model->course->name), 'url' => ['courses/update', 'id' => $model->courseId]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', $model->month->name), 'url' => ['months/update', 'id' => $model->monthId, 'courseId' => $model->courseId]];
$this->params['breadcrumbs'][] = Yii::t('app', $model->name);
?>
<div class="lessons-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'modelsVideo' => $modelsVideo,
    ]) ?>

</div>
