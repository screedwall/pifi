<?php

/* @var $this yii\web\View */

use kartik\tabs\TabsX;
use yii\bootstrap\NavBar;
use yii\bootstrap\Html;

$this->title = "Предметы";
$items = [];

$this->registerJs("
    var subjects = $('#subjects');
    subjects.on('show.bs.collapse','.collapse', function() {
        subjects.find('.collapse.in').collapse('hide');
    });
");

$subjectsContent = "";
?>

<h1>Предметы</h1>

<div id="subjects">
    <?php foreach(\app\controllers\AppController::getSubjects() as $subject): ?>
        <?php if (!empty($subject)){
        array_push($items, Html::button($subject, [
            'class' => "btn btn-default btn-lg collapsed",
            'data-toggle' => 'collapse',
            'data-target' => "#".str_replace(" ", "", $subject),
            'aria-expanded' => 'false',
            'aria-controls' => str_replace(" ", "", $subject),
            'data-parent' => '#subjects',
        ]));
            $subjectsContent .= Html::tag('div', $this->context->renderPartial('/courses/index', ['subject' => $subject]), [
                'class' => 'collapse',
                'id' => str_replace(" ", "", $subject)
            ]);
        }
        else
        {
            array_push($items, Html::button("Все", [
                    'class' => "btn btn-default btn-lg",
                    'data-toggle' => 'collapse',
                    'data-target' => "#all",
                    'aria-expanded' => 'true',
                    'aria-controls' => "all",
                    'data-parent' => '#subjects',
                ]));
            $subjectsContent .= Html::tag('div', $this->context->renderPartial('/courses/index'), [
                'class' => 'collapse in',
                'id' => 'all',
            ]);
        }

        ?>
    <?php endforeach; ?>
<?php
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

echo $subjectsContent;
?>

</div>