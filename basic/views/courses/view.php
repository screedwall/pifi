<?php
use kartik\tabs\TabsX;
use app\models\BoughtCourses;
use yii\bootstrap\Modal;
use yii\bootstrap\Html;
/* @var $this yii\web\View */
/* @var $model app\models\Courses */
$this->title = "Курс ".$model->name;
$oneMonth = count($model->months) == 1;
?>

<main>
<section class="course-head">
    <div class="container">
        <div class="row">
            <h1>Курс <?= $model->name ?></h1>
            <h3>Курс идет с <?= $model->dateFrom.' по '.$model->dateTo ?></h3>
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
                <?php if (!$oneMonth): ?>
                <p>Вы можете</p>
                <?php endif; ?>
                <?= Html::a('Купить курс', \yii\helpers\Url::to(['/pay', 'course' => $model->id]),
                    [
                        'data' => [
                            'method' => 'post',
                        ],
                        'class' => 'btn btn-success btn-lg btn-block',
                    ]
                ); ?>

                <?php
                if(!$oneMonth)
                {
                    echo "<p>или же</p>";
                    Modal::begin([
                        'header' => '<h3>Покупка разделов</h3>',
                        'toggleButton' => [
                            'label' => 'Купить разделы',
                            'class' => 'btn btn-primary btn-lg btn-block'
                        ],
                    ]);

                    echo Html::beginForm(['/pay'], 'GET');
                    foreach ($model->months as $month)
                    {
                        if(count($month->lessons) > 0) {
                            echo "<div class=\"form-group\">";
                            echo Html::checkbox('months[]', false, ['label' => "<span> " . $month->name . "</span>", 'value' => $month->id]);
                            echo "</div>";
                        }
                    }

                    echo "<div class=\"form-group\">";
                    echo Html::submitButton('Купить', ['class' => 'btn btn-success btn-lg btn-block']);
                    echo "</div>";
                    echo Html::endForm();


                    Modal::end();
                }
                ?>
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
