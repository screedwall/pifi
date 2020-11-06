<?php
use kartik\tabs\TabsX;
use app\models\BoughtCourses;
/* @var $this yii\web\View */
/* @var $model app\models\Courses */
$this->title = "Курс ".$model->name;
?>

<main>
<section class="course-head">
    <div class="container">
        <div class="row">
            <h1>Курс <?= $model->name ?></h1>
            <h3>Курс идет с <?= $model->dateFrom.' по '.$model->dateTo ?></h3>
            <h4><?= $model->subject ?>, готовимся к <?= $model->examType ?></h4>
            <h5><?= $model->shortDescription ?></h5>
        </div>
    </div>
</section>
<section class="course_main">
    <div class="container">
        <?php $this->beginBlock('description'); ?>
        <h2>Описание курса</h2>
        <p><?= $model->description ?></p>

        <?php $this->endBlock(); ?>

        <?php
        $items = [];
        $boughtMonths = Yii::$app->user->identity->getMonths()->select(['id'])->asArray()->all();

        foreach ($model->getMonths()->orderBy(['id' => SORT_ASC])->all() as $item) {
            $bought = false;
            foreach ($boughtMonths as $boughtMonth) {
                if(in_array($item->id, $boughtMonth))
                    $bought = true;
            }
            array_push($items, [
                'label' => '<i class="glyphicon glyphicon-book"></i> '.$item->name,
                'options' => ['id' => $item->name],
                'encode'=>false,
                'content' => $bought ? $this->render('/months/view', ['model' => $item]) : Yii::$app->user->identity->isAdmin() ? $this->render('/months/view', ['model' => $item]) : $this->render('/pay/index', ['id' => $item->id]) ]);
        }
        echo TabsX::widget([
            'items'=>[
                [
                    'label'=>'<i class="glyphicon glyphicon-home"></i> Об этом курсе',
                    'content' => $this->blocks['description'],
                    'active'=>true
                ],
                [
                    'label' => '<i class="glyphicon glyphicon-list"></i> Программа курса',
                    'items' => $items
                ],
            ],
            'position' => TabsX::POS_ABOVE,
            'encodeLabels' => false,
            'containerOptions' => [
                    'class' => 'col-md-8 col-xs-12',
            ]
        ]);
        ?>

        <div class="col-md-4 col-xs-12">
            <div class="section-buy">
                <p>Вы можете</p>
                <button  class="btn btn-success btn-lg btn-block">Купить весь курс</button>
                <p>или же</p>

                <div class="">
                    <div class="btn btn-primary btn-lg btn-block" data-toggle="collapse" data-parent="#accordion" href="#collapse1">
                            <p>Купить некоторые разделы</p>

                        <div id="collapse1" class="accordion__panel panel-collapse collapse" style="">
                            <div class="accordion_content">
                                <form>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Email address</label>
                                        <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Password</label>
                                        <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputFile">File input</label>
                                        <input type="file" id="exampleInputFile">
                                        <p class="help-block">Example block-level help text here.</p>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox"> Check me out
                                        </label>
                                    </div>
                                    <button type="submit" class="btn btn-default">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
<section class="course-teacher">
    <div class="container">
        <h2>Преподаватель</h2>
        <?= $this->render('/teachers/view', ['model' => $model->teacher, 'partial' => true]) ?>
    </div>
</section>
</main>
