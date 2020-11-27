<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Coupons */

$this->title = Yii::t('app', 'Update Coupons: {name}', [
    'name' => $model->code,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Coupons'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', $model->code);
?>
<div class="coupons-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
