<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\controllers\AppController;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Users'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'name',
            'email:email',
            'vk',
//            'description',
//            [
//                'attribute' => 'teacher',
//                'value' => 'teacher.name',
//                'label' => 'Преподаватель',
//            ],
            [
                'attribute' => 'role',
                'content' => function($model){
                    return AppController::ROLES[$model->role];
                },
                'filter' => [
                    '0' => AppController::ROLES[0],
                    '1' => AppController::ROLES[1],
                    '2' => AppController::ROLES[2],
                ]
            ],
//            'authKey',
//            'password',

            [
                    'class' => 'yii\grid\ActionColumn',
                    'template'=>'{update} {delete}',
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
