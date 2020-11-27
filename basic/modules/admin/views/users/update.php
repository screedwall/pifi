<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $roles app\models\Users */

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

    <h2>Потоки пользователя</h2>
    <?php
    echo GridView::widget([
        'dataProvider' => new \yii\data\ActiveDataProvider([
            'query' => $model->getStreams(),
        ]),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'course',
                'value' => 'course.name',
                'label' => 'Курс',
            ],
            [
                'attribute' => 'month',
                'value' => 'month.name',
                'label' => 'Поток',
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '',
                'buttons' => [
                    'delete' => function ($url, $model) {
                        return Html::a('', $url,
                            [
                                'title' => Yii::t('app', 'Удалить'),
                                'data' => [
                                    'method' => 'post',
                                    'confirm' => "Поток удалится без возможности востановления.\nПродолжить?",
                                ],
                                'class' => 'glyphicon glyphicon-trash'
                            ]
                        );
                    }
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    $url = Url::to(['users_stream/'.$action, 'id' => $model->id]);
                    return $url;
                }
            ],
        ],
    ]); ?>

</div>
