<?php
/* @var $this yii\web\View */
/* @var $model \app\models\Teachers */

use yii\bootstrap4\Carousel;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\helpers\Url;
if(!isset($partial)){
    $this->title = $model->name;
}

$arr = [];

foreach (\app\models\Courses::find()->where(['teacherId' => $model->id])->orderBy(['id' => SORT_ASC])->all() as $item)
    {
    if($item->getMonths()->count() == 0)
        continue;

        array_push($arr, '
            <div class="course-card">'
//            .Html::a('', Url::to(['/courses/view', 'id' => $item->id]), ['class' => 'course-card_link-wrapper',])
            .'<span class="course-card_title">'.$item->name.'</span>'
            .(!empty($item->thumbnail) ? "<img src='".$item->thumbnail."' class='course-card_cover'>" : null)
            .'<a class="course-card_author">'.$item->subject.' ['.$item->examType.']</a>'
            .'<span class="course-card_description">'.$item->description.'</span>'
            .'<span class="course-card_price">'.$item->price.' руб.</span>'
            .Html::a('Открыть', Url::to(['/courses/view', 'id' => $item->id]), ['class' => 'btn btn-primary course-card_action'])
        .'</div>');
    }

?>

<main>
    <section class="teacher-head">
        <div class="container">
            <div class="row">
                <h1><?= $model->name ?></h1>
                <h3>Основной предмет преподавателя - <?= $model->subject ?></h3>
            </div>
        </div>
    </section>
    <section class="teacher-splash hidden-xs" style="background-image: url(<?= $model->splash ?>)"></section>
    <section class="teacher-main">
        <div class="container text-center visible-xs">
            <img src="<?= $model->thumbnail ?>" alt="" class="teacher-thumbnail">
        </div>
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