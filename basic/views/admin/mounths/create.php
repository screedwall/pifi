<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\mounths */

$this->title = Yii::t('app', 'Create Mounths');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Courses'), 'url' => ['admin/courses/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', \app\models\Courses::findOne(['id'=>Yii::$app->request->get('id')])->name), 'url' => [Url::previous('course')]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="mounths-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
