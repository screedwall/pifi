<?php

use app\models\BoughtCourses;
use app\models\LessonsSearch;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\Users;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Courses */

$this->title = Yii::t('app', 'Update Courses: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Courses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', $model->name);
?>
<div class="courses-update">
    <h1><?= Html::encode($this->title) ?> <?= Html::a('<i class="glyphicon glyphicon-download"></i>', Url::to(['download', 'id' => $model->id]), ['class' => 'btn btn-lg btn-default', 'title' => 'Скачать учеников курса']) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<div class="months-index">
    <h1><a id="months"></a>Месяцы</h1>
    <p>
        <?php
            echo Yii::$app->user->identity->isAdmin() ? Html::a(Yii::t('app', 'Create Months'), ['months/create', 'courseId' => $model->id], ['class' => 'btn btn-success']) : '';
        ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php Pjax::begin(); ?>

    <?php
    echo GridView::widget([
        'dataProvider' => new \yii\data\ActiveDataProvider([
            'query' => $model->getMonths(),
        ]),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'dateFrom',
            'dateTo',

            ['class' => 'yii\grid\ActionColumn',
                'template'=>'{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('app', 'Просмотр'),
                        ]);
                    },

                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('app', 'Редактировать'),
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        if(Yii::$app->user->identity->isTeacher())
                            return false;
                        return Html::a('', $url,
                            [
                                'title' => Yii::t('app', 'Удалить'),
                                'data' => [
                                    'method' => 'post',
                                    'confirm' => "Удалится месяц и все вложенные уроки.\nПродолжить?",
                                ],
                                'class' => 'glyphicon glyphicon-trash'
                            ]
                        );
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if($action==='view'){
                        $url = Url::to(['/months/view', 'id' => $model->id]);
                        return $url;
                    }elseif ($action==='users'){

                    }
                    else{
                        $url = Url::to(['months/'.$action, 'id' => $model->id, 'courseId' => Yii::$app->request->get('id')]);
                        return $url;
                    }
                }
            ],

        ],
    ]);
    ?>

    <?php Pjax::end(); ?>

</div>