<?php

/* @var $this yii\web\View */

use yii\bootstrap\NavBar;
use yii\bootstrap\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;

$this->title = "Курсы";
$items = [];

?>

<h1>Курсы</h1>

<?php Pjax::begin() ?>

<div id="subjects">
    <?php foreach(\app\controllers\AppController::SUBJECTS as $subject): ?>
        <?php if (!empty($subject)){
            $subjectItems = [];

            foreach (\app\controllers\AppController::EXAM_TYPES as $exam)
            {
                array_push($subjectItems, "<li>".Html::a($exam, Url::to(['/courses', 'subject' => $subject, 'exam' => $exam]))."</li>");
            }

            array_push($subjectItems, '<li role="separator" class="divider"></li>');
            array_push($subjectItems, "<li>".Html::a('Все', Url::to(['/courses', 'subject' => $subject]))."</li>");

            array_push($items, [
                'label' => $subject,
                'url' => ['/courses', 'subject' => $subject],
                'options' => ['class' => "item-btn ".($subjectRequest != $subject ? "collapsed" : null)],
                'items' => $subjectItems,
            ]);
        }
        else
        {
            array_push($items, [
                'label' => 'Все',
                'url' => ['/courses'],
                'options' => ['class' => "item-btn ".(empty($subjectRequest) ?  null : "collapsed")],
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
    ?>
<div class="container">
    <div class="row">
        <?php
        $valuable = 0;

        foreach ($model as $item) : ?>
            <?php

                if($item->isVisible or $item->isSpec)
                    continue;

                $skip = true;
                foreach ($item->months as $month)
                {
                    if(count($month->lessons) > 0)
                        $skip = false;
                }

                if($skip)
                    continue;
                else
                    $valuable++;

            ?>
            <div class="col-md-4 col-sm-6 course-item">
                <div class="course-card">
                    <?php
                    if(Yii::$app->user->isGuest)
                    {
                        $action = '/auth/login';
                        $param = "courseId";
                        $label = "Записаться";
                    }
                    else{
                        $action = '/courses/view';
                        $param = "id";
                        $label = "Открыть";
                    }
                    ?>
                    <?= Html::a('', Url::to(['/courses/view', 'id' => $item->id]), ['class' => 'course-card_link-wrapper']) ?>
                    <span class="course-card_title"><?= $item->name ?></span>
                    <?= (!empty($item->thumbnail) ? "<img src='$item->thumbnail' class='course-card_cover'>" : null) ?>
                    <a href="<?= Url::to(['teachers/'.$item->teacherId]) ?>" class="course-card_author"><?= $item->subject." [".$item->examType."] / ".$item->teacher->name ?></a>
                    <span class="course-card_description"><?= $item->description ?></span>
                    <span class="course-card_price"><?= $item->price() ?> руб.</span>

                    <?= Html::a('Открыть', Url::to(['/courses/view', 'id' => $item->id]), [
                        'class' => 'btn btn-primary course-card_action',
                    ]) ?>
                </div>
            </div>
        <?php endforeach; ?>
        <?= $valuable == 0 ? "<div class='col-md-12'><h4>Курсов в такой категории пока нет</h4></div>" : null ?>
    </div>

</div>

<?php Pjax::end() ?>
</div>
