<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\mounths */

$this->title = $model->name;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Courses'), 'url' => ['admin/courses/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', $model->course), 'url' => [Url::previous('course')]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="mounths-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Изменить'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Удалить'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Вы уверены, что хотите удалить данную запись?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'dateFrom',
            'dateTo',
            'course',
        ],
    ]) ?>

</div>


<div class="lessons-index">
    <h1>Уроки</h1>
    <p>
        <?= Html::a(Yii::t('app', 'Create Lessons'), Url::to(['admin/lessons/create', 'id'=>$model->id, 'courseId'=>Yii::$app->request->get('courseId')]), ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php
//    Url::remember(Url::current(),'mounth');
    echo GridView::widget([
        'dataProvider' => new \yii\data\ActiveDataProvider([
            'query' => \app\models\Lessons::find()->where(['mounth'=>$model->id]),
        ]),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'name',
            'shortDescription',
//            'description:ntext',
            'video',
            'lessonDate',
            'homeworkDate',
//            'mounth',
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
                    $url = Url::to(['admin/lessons/'.$action, 'id' => $model->id, 'courseId' => Yii::$app->request->get('id')]);
                    return $url;
                }
            ],
        ],
    ]); ?>

</div>
