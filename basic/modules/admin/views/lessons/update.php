<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Lessons */
/* @var $courseId app\models\Lessons */
/* @var $mounthId app\models\Lessons */

$this->title = Yii::t('app', 'Update Lessons: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Courses'), 'url' => ['courses/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', \app\models\Courses::findOne(['id' => $courseId])->name), 'url' => ['courses/update', 'id' => $courseId]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', \app\models\Mounths::findOne(['id' => $mounthId])->name), 'url' => ['mounths/update', 'id' => $mounthId, 'courseId' => $courseId]];
$this->params['breadcrumbs'][] = Yii::t('app', $model->name);
?>
<div class="lessons-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'courseId' => $courseId,
        'mounthId' => $mounthId
    ]) ?>

</div>