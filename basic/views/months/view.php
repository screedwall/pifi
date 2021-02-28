<?php
/* @var $this yii\web\View */
/* @var $model \app\models\Months */
/* @var $demo boolean */
use yii\data\ActiveDataProvider;
use yii\widgets\ListView;
use yii\bootstrap\Collapse;
use yii\helpers\Html;
use yii\helpers\Url;
use app\controllers\AppController;

$this->title = "Расписание на ".mb_strtolower($model->name);
?>

<h2><?= $this->title ?></h2>
<?php
$items = [];

foreach ($model->lessons as $item) {
    array_push($items, [
                'label' => $item->lessonDate.": ".$item->name,
                'options' => ['clientOptions' => $item->name],
                'content' => "Время выполнения ДЗ: ".$item->homeworkDate."<br>"
                .Html::a('Открыть урок', ['lessons/view', 'id' => $item->id], [
                    'class' => 'btn btn-primary',
                ])
    ]);
}
echo Collapse::widget([
   'items' => $items
]);

if($demo)
    echo Html::a('<i class="glyphicon glyphicon-ruble"></i> Купить полный месяц', Url::to(['/pay', 'course' => $model->courseId, 'month' => $model->id, 'type' => AppController::STREAM_TYPE_DEMO_CONTINUATION]), [
        'class' => 'btn btn-success btn-block',
    ]);