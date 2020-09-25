<?php
use yii\bootstrap\Tabs;
use app\models\BoughtCourses;
/* @var $this yii\web\View */
/* @var $model app\models\Courses */
$this->title = "Курс ".$model->name;
?>
<h1>Курс <?= $model->name ?></h1>

<?php
$items = [];
$boughtMonths = Yii::$app->user->identity->getMonths()->select(['id'])->asArray()->all();

foreach ($model->months as $item) {
    $bought = false;
    foreach ($boughtMonths as $boughtMonth) {
        if(in_array($item->id, $boughtMonth))
            $bought = true;
    }
    array_push($items, [
        'label' => $item->name,
        'options' => ['id' => $item->name],
        'content' => $bought ? $this->render('/months/view', ['model' => $item]) : Yii::$app->user->identity->isAdmin() ? $this->render('/months/view', ['model' => $item]) : $this->render('/pay/index', ['id' => $item->id]) ]);
}
echo Tabs::widget([
   'items' => $items
]);
?>
<h2>Преподаватель</h2>
    <?= $model->teacher->name ?>
    <br>
    <?= $model->teacher->subject ?>