<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\CoursesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Courses');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="courses-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Courses'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
//            'shortDescription',
            [
                'value' => function ($model) {
                    return  $model->dateFrom();
                },
                'label' => 'Дата начала',
            ],
            [
                'value' => function ($model) {
                    return  $model->dateTo();
                },
                'label' => 'Дата окончания',
            ],
            [
                'attribute' => 'teacher',
                'value' => 'teacher.name',
                'label' => 'Преподаватель',
            ],
            'subject',
            'examType',

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {copy}',
                'urlCreator' => function ($action, $model, $key, $index) {
                    if($action==='view'){
                        $url = Url::to(['/courses/view', 'id' => $model->id]);
                        return $url;
                    }
                    $url = Url::to(['courses/'.$action, 'id' => $model->id]);
                    return $url;
                },

                'buttons' => [
                    'copy' => function ($url, $model) {
                        return Html::a('', $url,
                            [
                                'title' => Yii::t('app', 'Скопировать'),
                                'data' => [
                                    'method' => 'post',
                                ],
                                'class' => 'glyphicon glyphicon-duplicate'
                            ]
                        );
                    },
                    'view' => function ($url, $model) {
                        if(!$model->isSpec)
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                'title' => Yii::t('app', 'Просмотр'),
                            ]);
                        else
                            return null;
                    },
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
