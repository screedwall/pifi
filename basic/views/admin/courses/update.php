<?php

use app\models\LessonsSearch;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Courses */

$this->title = Yii::t('app', 'Update Courses: {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Courses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', $model->name);
?>
<div class="courses-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<div class="mounths-index">
    <h1><a id="mounths"></a>Месяцы</h1>
    <p>
        <?= Html::a(Yii::t('app', 'Create Mounths'), ['admin/mounths/create', 'courseId' => $model->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php Pjax::begin(); ?>

    <?php
    $searchModel = new \app\models\MounthsSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    $dataProvider->query = $searchModel::find()->with('course')->where(['course' => $model->id]);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'dateFrom',
            'dateTo',

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
                                    'confirm' => "Удалится месяц и все вложенные уроки.\nПродолжить?",
                                ],
                                'class' => 'glyphicon glyphicon-trash'
                            ]
                        );
                    }
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    $url = Url::to(['admin/mounths/'.$action, 'id' => $model->id, 'courseId' => Yii::$app->request->get('id')]);
                    return $url;
                }
            ],

        ],
    ]);
    ?>

    <?php Pjax::end(); ?>

</div>