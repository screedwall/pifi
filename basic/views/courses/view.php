<?php

use app\controllers\AppController;
use kartik\tabs\TabsX;
use app\models\BoughtCourses;
use yii\bootstrap\Modal;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Courses */
$this->title = "Курс ".$model->name;

$count = 0;
if(count($model->months) >0)
    foreach ($model->months as $month)
        if(count($month->lessons) > 0)
            $count++;

$oneMonth = $count == 1;

$bought = false;
if(!Yii::$app->user->isGuest)
    foreach(Yii::$app->user->identity->courses as $course)
        if($model->id == $course->id)
            $bought = true;
?>

<main>
<section class="course-head">
    <div class="container">
        <div class="row">
            <h1>Курс <?= $model->name ?></h1>
            <h3>Курс идет с <?= $model->dateFrom().' по '.$model->dateTo() ?></h3>
            <h4><?= $model->subject ?>, готовимся к <?= $model->examType ?></h4>
            <h5><?= $model->shortDescription ?></h5>
        </div>
    </div>
</section>
<section class="course_main">
    <div class="container">
        <?php $this->beginBlock('description'); ?>
        <h2>Описание курса</h2>
        <p><?= $model->description ?></p>

        <?php $this->endBlock(); ?>

        <?php $this->beginBlock('schedule'); ?>
        <h2>Расписание</h2>
        <?php
            $items = [];

            foreach ($model->months as $month) {
                if(count($month->lessons) > 0){
                    $lessons = "<ol>";
                    foreach ($month->lessons as $lesson) {
                        $lessons = $lessons."<li class='lesson-item'>$lesson->name</li>";
                    }
                    $lessons = $lessons."</ol>";
                    array_push($items, [
                        'label' => $month->name,
                        'content' => $lessons,
//                        'contentOptions' => [
//                                'class' => 'in'
//                        ]
                    ]);
                }
            }

            echo \yii\bootstrap\Collapse::widget([
                'items' => $items,
                'autoCloseItems' => false,
                'options' => ['class' => 'lessons-panel'],
                'itemToggleOptions' => [
                    'tag' => 'div',
                ],
            ]);
        ?>

        <?php $this->endBlock(); ?>

        <?php

        echo TabsX::widget([
            'items'=>[
                [
                    'label'=>'<i class="glyphicon glyphicon-home"></i> Об этом курсе',
                    'content' => $this->blocks['description'],
                    'active' => true,
                ],
                [
                    'label' => '<i class="glyphicon glyphicon-list"></i> Программа курса',
                    'content' => $this->blocks['schedule'],
                ],
            ],
            'position' => TabsX::POS_ABOVE,
            'encodeLabels' => false,
            'containerOptions' => [
                    'class' => 'col-md-8 col-xs-12',
            ]
        ]);
        ?>

        <div class="col-md-4 col-xs-12">
            <div class="section-buy">
                <?php if(Yii::$app->user->isGuest): ?>
                    <p><?=Html::a('Войдите', \yii\helpers\Url::to(['/auth/login'])) ?> чтобы приобретать курсы.</p>
                <?php else: ?>
                    <?php if(!$bought): ?>
                        <p>Вы можете купить:</p>
                        <?php
                        $types = [];
                        foreach (AppController::STREAM_TYPES as $STREAM_TYPE)
                            array_push($types, [
                                'id' => $STREAM_TYPE,
                                'name' => AppController::getStreamType($STREAM_TYPE)
                            ]);

                        $toDelete = AppController::STREAM_CONTINUATIONS;
                        foreach ($toDelete as $el) {
                            ArrayHelper::removeValue($types, [
                                'id' => $el,
                                'name' => AppController::getStreamType($el),
                            ]);
                        }

                        foreach ($types as $type)
                            echo Html::a($type['name'], \yii\helpers\Url::to(['/pay', 'course' => $model->id, 'type' => $type['id']]),
                                [
                                    'class' => 'btn btn-success btn-lg btn-block',
                                ]
                            );
                        ?>

                    <?php else: ?>
                        <p>Курс приобретен.</p>
                        <p>Посмотреть его можно в <?=Html::a('профиле', \yii\helpers\Url::to(['/profile'])) ?>.</p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>
</section>
<section class="course-teacher">
    <div class="container">
        <h2>Преподаватель</h2>
        <?= $this->render('/teachers/view', ['model' => $model->teacher, 'partial' => true]) ?>
    </div>
</section>
</main>
