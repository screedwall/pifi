<?php
/* @var $this yii\web\View */
/* @var $model \app\models\Months */
use yii\data\ActiveDataProvider;
use yii\widgets\ListView;
use yii\bootstrap\Collapse;
use yii\helpers\Html;
?>

<h2>Расписание на <?= mb_strtolower($model->name) ?></h2>
<?php
$items = [];

foreach ($model->lessons as $item) {
    array_push($items, [
                'label' => $item->name,
                'options' => ['clientOptions' => $item->name],
                'content' => "Время урока: ".$item->lessonDate."<br>"
                ."Время выполнения ДЗ: ".$item->homeworkDate."<br>"
                .Html::a(
                        'Открыть урок',
                        ['lessons/view', 'id' => $item->id],
                        ['class' => 'btn btn-primary']
                    )
    ]);
}
echo Collapse::widget([
   'items' => $items
]);