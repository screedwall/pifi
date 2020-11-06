<?php
/* @var $this yii\web\View*/
/* @var $model app\models\Teachers */
use yii\helpers\Url;
use yii\helpers\Html;
$this->title = "Преподаватели";
?>

<h1>Преподаватели</h1>
<div class="row">
    <?php
    foreach ($model as $item) : ?>
        <div class="col-md-4 col-sm-6 teacher-item">
            <div class="teacher-card">
                <span class="teacher-card_name"><?= $item->name ?></span>
                <?= (!empty($item->thumbnail) ? Html::a("<img src='$item->thumbnail' class='teacher-card_img'>", Url::to(['/teachers/view', 'id' => $item->id]), [
                    'class' => 'teacher-card_cover',
                ])  : null) ?>
                <span class="teacher-card_description"><?= $item->description ?></span>
                <?= Html::a('Написать в ВК', Url::to('https://'.$item->contact), [
                    'class' => 'btn btn-primary teacher-card_vk',
                ]) ?>
                <?= Html::a('Подробнее', Url::to(['/teachers/view', 'id' => $item->id]), [
                    'class' => 'btn btn-success teacher-card_action',
                ]) ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>