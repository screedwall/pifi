<?php
/* @var $this yii\web\View */
/* @var $model \app\models\Teachers */

use yii\bootstrap4\Carousel;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\helpers\Url;
use app\models\Courses;

if(!isset($partial)){
    $this->title = $model->name;
}

$arr = [];

$courses = Courses::find()
            ->where(['teacherId' => $model->id])
            ->orderBy(['id' => SORT_ASC])
            ->with('months')
            ->with('lessons')
            ->all();

foreach ($courses as $course)
    {
    if ($course->isVisible)
        continue;
    if(count($course->lessons) == 0)
        continue;

        array_push($arr, '
            <div class="course-card">'
            .'<span class="course-card_title">'.$course->name.'</span>'
            .(!empty($course->thumbnail) ? "<img src='".$course->thumbnail."' class='course-card_cover'>" : null)
            .'<a class="course-card_author">'.$course->subject.' ['.$course->examType.']</a>'
            .'<span class="course-card_description">'.$course->description.'</span>'
            .'<span class="course-card_price">'.$course->price().' руб.</span>'
            .Html::a('Открыть', Url::to(['/courses/view', 'id' => $course->id]), ['class' => 'btn btn-primary course-card_action'])
        .'</div>');
    }

?>

<main>
    <section class="teacher-head">
        <div class="container">
            <div class="row">
                <h1><?= $model->name ?></h1>

                <?php if(!empty($model->subject)): ?>
                    <h3>Основной предмет преподавателя - <?= $model->subject ?></h3>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php if(!empty($model->splash)): ?>
    <section class="teacher-splash hidden-xs" style="background-image: url(<?= $model->splash ?>)"></section>
    <?php endif; ?>
    <section class="teacher-main">
        <?php if(!empty($model->thumbnail)): ?>
        <div class="container text-center visible-xs">
            <img src="<?= $model->thumbnail ?>" alt="" class="teacher-thumbnail">
        </div>
        <?php endif; ?>
        <div class="container">
            <h2>О преподавателе</h2>
            <p><?= $model->description ?></p>

        </div>
    </section>
    <section class="courses-teacher hidden-xs">
        <div class="container">
            <?php if(!empty($arr)): ?>

            <h2>Курсы преподавателя</h2>
            <?= \evgeniyrru\yii2slick\Slick::widget([
                // HTML tag for container. Div is default.
                'itemContainer' => 'div',

                // HTML attributes for widget container
                'containerOptions' => ['class' => 'teacher_courses-slider'],

                // Items for carousel. Empty array not allowed, exception will be throw, if empty
                'items' => $arr,

                // HTML attribute for every carousel item
                'itemOptions' => ['class' => 'carousel_teacher-item'],

                // settings for js plugin
                // @see http://kenwheeler.github.io/slick/#settings
                'clientOptions' => [
                    'autoplay' => true,
                    'dots'     => true,
                    'infinite' => true,
                    'arrows' => true,
                    'slidesToShow' => 3,
                    'variableWidth' => true,
                ],
            ])?>

            <?php endif; ?>
        </div>
    </section>
</main>