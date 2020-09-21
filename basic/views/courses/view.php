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

foreach ($model->mounths as $item) {
    $search = BoughtCourses::find()->where(['mounth' => $item->id])->andWhere(['user' => Yii::$app->user->identity->getId()])->all();
    array_push($items, [
        'label' => $item->name,
        'options' => ['id' => $item->name],
        'content' => (count($search) > 0) ? $this->render('/mounths/view', ['model' => \app\models\Mounths::findOne($item->id)]) : Yii::$app->user->identity->isAdmin() ? $this->render('/mounths/view', ['model' => \app\models\Mounths::findOne($item->id)]) : $this->render('/pay/index', ['id' => $item->id]) ]);
}
echo Tabs::widget([
   'items' => $items
]);
?>
<h2>Преподаватель</h2>
    <?= $model->teacher ?>
    <br>
    <?= $model->teacherr->subject ?>