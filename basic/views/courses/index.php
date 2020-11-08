<?php
/* @var $this yii\web\View*/
/* @var $model app\models\Courses */

use app\models\Courses;
use yii\helpers\Url;
use yii\helpers\Html;
$this->title = "Курсы";

if(isset($subject))
    $model = Courses::find()->where(['subject' => $subject])->orderBy(['id' => SORT_ASC])->all();
else
    $model = Courses::find()->orderBy(['id' => SORT_ASC])->all();

?>

<h1>Курсы</h1>
<div class="row">
    <?php
    if(count($model) == 0)
        echo "<h4>Курсов по этому предмету пока нет</h4>";
    foreach ($model as $item) : ?>
        <?php

            if(count($item->months) == 0)
                continue;

            $skip = true;
            foreach ($item->months as $month)
            {
                if(count($month->lessons) > 0)
                    $skip = false;
            }
            if($skip)
                continue;

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
                <span class="course-card_price"><?= $item->price ?> руб.</span>

                <?= Html::a('Открыть', Url::to(['/courses/view', 'id' => $item->id]), [
                        'class' => 'btn btn-primary course-card_action',
                ]) ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>