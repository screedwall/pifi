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
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'shortDescription',
            'dateFrom',
            'dateTo',
            'teacher',
            'subject',
            'examType',
            'price',

            ['class' => 'yii\grid\ActionColumn',
                'urlCreator' => function ($action, $model, $key, $index) {
                    if($action==='view'){
                        $url = Url::to(['/courses/view', 'id' => $model->id]);
                        return $url;
                    }
                    $url = Url::to(['courses/'.$action, 'id' => $model->id]);
                    return $url;
                }
                ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
