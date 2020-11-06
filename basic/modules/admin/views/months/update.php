<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Months */

$this->title = Yii::t('app', 'Update Months: {name}', [
    'name' => $model->name,
]);

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Courses'), 'url' => ['courses/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', $model->course->name), 'url' => ['courses/update', 'id' => $model->courseId, '#' => 'months']];
$this->params['breadcrumbs'][] = Yii::t('app', $model->name);
?>
<div class="months-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model
    ]) ?>

</div>

<div class="lessons-index">
    <h1>Уроки</h1>
    <p>
        <?= Html::a(Yii::t('app', 'Create Lessons'), Url::to(['lessons/create', 'courseId' => $courseId, 'monthId'=>$model->id]), ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    echo GridView::widget([
        'dataProvider' => new \yii\data\ActiveDataProvider([
            'query' => $model->getLessons(),
        ]),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'name',
//            'shortDescription',
//            'description:ntext',
            'video',
            'lessonDate',
            'homeworkDate',
//            'month',
            //'course',

            ['class' => 'yii\grid\ActionColumn',
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
                        return Html::a('', $url,
                            [
                                'title' => Yii::t('app', 'Удалить'),
                                'data' => [
                                    'method' => 'post',
                                    'confirm' => "Урок удалится без возможности востановления.\nПродолжить?",
                                ],
                                'class' => 'glyphicon glyphicon-trash'
                            ]
                        );
                    }
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if($action==='view'){
                        $url = Url::to(['/lessons/view', 'id' => $model->id]);
                        return $url;
                    }
                    $url = Url::to(['lessons/'.$action, 'id' => $model->id, 'courseId' => Yii::$app->request->get('courseId'), 'monthId' => Yii::$app->request->get('id')]);
                    return $url;
                }
            ],
        ],
    ]); ?>

</div>