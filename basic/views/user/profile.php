<?php

/* @var $courses app\models\Courses */
/* @var $months app\models\Months */

use kartik\tabs\TabsX;
use yii\bootstrap\NavBar;
use yii\widgets\ActiveForm;
use yii\bootstrap\Html;
use yii\bootstrap\Modal;

$this->title = "Личный кабинет";

$this->registerJs("
    var subjects = $('#subjects');
    subjects.on('show.bs.collapse','.collapse', function() {
        subjects.find('.collapse.in').collapse('hide');
    });
");

function isIntersect($array, $checkingValue)
{
    foreach ($array as $el)
    {
        if($el == $checkingValue)
            return true;
    }
    return false;
}

function RusEnding($n, $n1, $n2, $n5) {
    if($n >= 11 and $n <= 19) return $n5;
    $n = $n % 10;
    if($n == 1) return $n1;
    if($n >= 2 and $n <= 4) return $n2;
    return $n5;
}

$teachersRaw = [];
$teachersData = [];

$subjects = [];

foreach ($courses as $course)
    if(!isIntersect($subjects, $course->subject))
        array_push($subjects, $course->subject);

?>

<h1>Личный кабинет</h1>
<div class="row">
    <?php $this->beginBlock('courses'); ?>
        <?php foreach ($subjects as $subject): ?>
        <?php $this->beginBlock($subject) ?>
            <?php foreach (Yii::$app->user->identity->getCoursesBySubject($subject)->all() as $course): ?>

            <?php
                array_push($teachersRaw, $course->teacher);
            ?>

            <div class="profile-card">
                <p><?= $course->name ?></p>
                <?php foreach ($course->months as $month): ?>
                    <?php
                        if($month->getLessons()->count() == 0)
                            continue;
                    ?>
                <div class="row">
                    <div class="profile-month col-md-9">
                        <p><?= $month->name ?></p>
                    </div>
                    <div class="profile-action col-md-3 text-center">
                         <?= (isIntersect($months, $month) ?
                             Html::a('<i class="glyphicon glyphicon-eye-open"></i> Открыть', \yii\helpers\Url::to(['/months/'.$month->id]), [
                                 'class' => 'btn btn-primary btn-block',
                             ])
                            : Html::a('<i class="glyphicon glyphicon-ruble"></i> Купить', \yii\helpers\Url::to(['/pay', 'course' => $course->id, 'month' => $month->id, 'type' => 'month']), [
                                 'class' => 'btn btn-success btn-block',
                             ]))
                         ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php
                foreach ($teachersRaw as $teacher)
                {
                    if(!isIntersect($teachersData, $teacher))
                        array_push($teachersData, $teacher);
                }
                $teachers = [];
                foreach ($teachersData as $teacher)
                {
                    $coursesCount = 0;
                    foreach ($teachersRaw as $rawTeacher)
                        if($teacher == $rawTeacher)
                            $coursesCount++;
                    array_push($teachers, ['data' => $teacher, 'count' => $coursesCount]);
                }
            ?>
            <?php endforeach; ?>
        <?php $this->endBlock() ?>
        <?php endforeach; ?>

        <div id="subjects">

            <?php
                $items = [];
                $overallContent = "";
                $subjectsContent = "";
                foreach($subjects as $subject)
                {
                    $overallContent .= $this->blocks[$subject];
                }
                array_push($items, Html::button("Все", [
                    'class' => "btn btn-default btn-lg",
                    'data-toggle' => 'collapse',
                    'data-target' => "#all",
                    'aria-expanded' => 'true',
                    'aria-controls' => "all",
                    'data-parent' => '#subjects',
                ]));
                $subjectsContent .= Html::tag('div', $overallContent, [
                    'class' => 'collapse in',
                    'id' => 'all',
                ]);
            ?>
            <?php
                if(count($courses) > 0)
                {
                    echo '<h2 class="text-center">Мои курсы</h2>';
                    foreach($subjects as $subject)
                    {
                        array_push($items, Html::button($subject, [
                            'class' => "btn btn-default btn-lg collapsed",
                            'data-toggle' => 'collapse',
                            'data-target' => "#".str_replace(" ", "", $subject),
                            'aria-expanded' => 'false',
                            'aria-controls' => str_replace(" ", "", $subject),
                            'data-parent' => '#subjects',
                        ]));
                        $subjectsContent .= Html::tag('div', $this->blocks[$subject], [
                            'class' => 'collapse',
                            'id' => str_replace(" ", "", $subject)
                        ]);
                    }
                    NavBar::begin([
                        'options' => [
                            'class' => 'nav-subjects navbar-default',
                        ],
                    ]);
                    echo \yii\bootstrap\Nav::widget([
                        'options' => [
                            'class' => 'navbar-nav navbar-center',
                        ],
                        'encodeLabels' => true,
                        'items' => $items,
                    ]);

                    NavBar::end();
                }else
                    echo "<h3 class='text-center'>У вас пока нет приобретенных курсов.</h3>
                          <h3 class='text-center'>Но их всегда можно приобрести "
                          .Html::a('здесь.', \yii\helpers\Url::to(['/courses']))."</h3>";
            ?>
            <div class="profile-cards two-thirds">
                <?=$subjectsContent?>
            </div>
        </div>
    <?php $this->endBlock(); ?>

    <?php $this->beginBlock('teachers'); ?>
        <h2 class="text-center">Мои преподаватели</h2>

        <div class="profile-cards">
            <?php if(count($courses) > 0): ?>
                <?php foreach ($teachers as $teacher): ?>
                    <div class="profile-card">
                        <div class="row">
                            <div class="col-md-2 text-center">
                                <img src="<?=$teacher['data']->thumbnail?>" class="teacher-thumbnail">
                            </div>
                            <div class="col-md-7">
                                <p><?=$teacher['data']->name?></p>
                                <p>Ведёт у вас <?=$teacher['count']?> <?= RusEnding($teacher['count'], "курс", "курса", "курсов") ?>.</p>
                            </div>
                            <div class="profile-action col-md-3 text-center">
                                <?=Html::a('<i class="glyphicon glyphicon-eye-open"></i> Открыть', \yii\helpers\Url::to(['/teachers/'.$teacher['data']->id]), [
                                    'class' => 'btn btn-primary btn-block',
                                ])?>
                                <?=Html::a('<i class="glyphicon glyphicon-pencil"></i> Написать', \yii\helpers\Url::to('https://'.$teacher['data']->contact), [
                                    'class' => 'btn btn-success btn-block',
                                ])?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    <?php $this->endBlock(); ?>

    <?php $this->beginBlock('profile'); ?>
        <h2>Мой профиль</h2>
        <div class="users-form">

            <?php $form = ActiveForm::begin(); ?>

            <div class="form-group">
                <?= Html::label('Логин', 'users-login') ?>
                <?= Html::textInput('users-login', $model->login, [
                    'class' => 'form-control',
                    'disabled' => true,
                ]) ?>
            </div>

            <div class="form-group">
                <?= Html::label('Ссылка на VK', 'users-vk') ?>
                <?= Html::textInput('users-vk', $model->vk, [
                    'class' => 'form-control',
                    'disabled' => true,
                ]) ?>
            </div>

            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'description')->textarea(['maxlength' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Сохранить'), ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>

            <?php if(!isset($new)): ?>
                <div class="form-group">
                    <?= Html::label('Смена пароля', 'users-password') ?>
                    <?php Modal::begin([
                        'header' => '<h3>Смена пароля</h3>',
                        'toggleButton' => [
                            'label' => 'Сменить пароль',
                            'class' => 'btn btn-primary btn-lg btn-block'
                        ],
                    ]); ?>

                    <?php
                    echo Html::beginForm(['change-password'], 'POST');

                    echo "<div class=\"form-group\">";
                    echo Html::label('Введите пароль', 'password');
                    echo Html::textInput('password', '', [
                        'class' => 'form-control',
                        'minlength' => 6
                    ]);
                    echo "</div>";

                    echo "<div class=\"form-group\">";
                    echo Html::submitButton('Изменить', ['class' => 'btn btn-success btn-lg btn-block']);
                    echo "</div>";

                    echo Html::endForm();
                    ?>

                    <?php Modal::end(); ?>
                </div>
            <?php endif; ?>

        </div>
    <?php $this->endBlock(); ?>

    <?php
        $tabsItems = [
            [
                'label'=>'<i class="glyphicon glyphicon-education"></i> Курсы',
                'content' => $this->blocks['courses'],
                'active' => true,
            ],
        ];

        if(count($courses) > 0)
            array_push($tabsItems, [
                'label' => '<i class="glyphicon glyphicon-user"></i> Преподаватели',
                'content' => $this->blocks['teachers'],
            ]);

        array_push($tabsItems, [
                'label' => '<i class="glyphicon glyphicon-cog"></i> Профиль',
                'content' => $this->blocks['profile'],
            ]);

        echo TabsX::widget([
            'items' => $tabsItems,
            'position' => TabsX::POS_ABOVE,
            'encodeLabels' => false,
            'containerOptions' => [
                'class' => 'col-md-12',
            ]
        ]);
    ?>

</div>
