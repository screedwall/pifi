<?php
/* @var $this yii\web\View*/
/* @var $model app\models\Courses */
use yii\helpers\Url;
use yii\helpers\Html;
$this->title = "Курсы";
?>

<h1>Курсы</h1>
<div class="row">
    <?php
    foreach ($model as $item) : ?>
        <?php
            if($item->getMonths()->count() == 0)
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