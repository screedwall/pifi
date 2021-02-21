<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $roles app\models\Users */

/* @var $searchModel app\models\TinkoffPaySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Update Users: {name}', [
    'name' => $model->login,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name];
?>
<div class="users-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model
    ]) ?>

</div>

<div class="users-months">
    <h2>Месяцы пользователя</h2>
    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            [
                'attribute' => 'course',
                'value' => 'month.course.name',
                'label' => 'Курс',
            ],
            [
                'attribute' => 'Месяц',
                'value' => 'month.name',
                'label' => 'Месяц',
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>
