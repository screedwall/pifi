<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Lessons */
/* @var $courseId app\models\Lessons */
/* @var $mounthId app\models\Lessons */

$this->title = Yii::t('app', 'Create Lessons');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Courses'), 'url' => ['admin/courses/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', \app\models\Courses::findOne(['id' => $courseId])->name), 'url' => ['admin/courses/update', 'id' => $courseId]];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', \app\models\Mounths::findOne(['id' => $mounthId])->name), 'url' => ['admin/mounths/update', 'id' => $mounthId, 'courseId' => $courseId]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create Lessons');
?>
<div class="lessons-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'courseId' => $courseId,
        'mounthId' => $mounthId,
    ]) ?>

</div>
