<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\mounths */
/* @var $courseId app\models\mounths */

$this->title = Yii::t('app', 'Create Mounths');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Courses'), 'url' => ['admin/courses/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', \app\models\Courses::findOne(['id' => $courseId])->name), 'url' => ['admin/courses/update', 'id' => $courseId]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="mounths-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'courseId' => $courseId,
    ]) ?>

</div>
