<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;
use kartik\depdrop\DepDrop;
use kartik\select2\Select2;
use app\models\Months;
use yii\helpers\ArrayHelper;
use app\controllers\AppController;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $roles app\models\Users */

/* @var $searchModel app\models\TinkoffPaySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Update Users: {name}', [
    'name' => $model->login,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name];

$user = $model;

$JS = <<<JS
    $(function(){
          $(document).on('click', '.showModalButton', function(){
            if ($('#updateMonthUser').data('bs.modal').isShown) {
                $('#updateMonthUser').find('#modalContent')
                        .load($(this).attr('href'));
                document.getElementById('modalHeader').innerHTML 
                      = '<button type="button" class="close" data-dismiss="modal">&times;</button>'
                      + '<h4>' + $(this).attr('name') + '</h4>';
            } else {
                $('#updateMonthUser').modal('show')
                        .find('#modalContent')
                        .load($(this).attr('href'));
                document.getElementById('modalHeader').innerHTML
                      = '<button type="button" class="close" data-dismiss="modal">&times;</button>'
                      + '<h4>' + $(this).attr('name') + '</h4>';
            }
        });
          $('#updateMonthUser').on('hidden.bs.modal', function (e) {
              $('#modalHeader').html('<h4>Загрузка...</h4>');
              $('#modalContent').html('<div style="text-align:center"><img class="load-gif" src="/img/load.gif"></div>');
              $.pjax.reload({container: '#user-months',  async: false});
          });
    });
JS;

$this->registerJs($JS);
?>
<div class="users-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model
    ]) ?>

</div>

<div class="users-months">
    <h2>Месяцы пользователя</h2>
    <div class="create-month-user">
        <div class="row">
            <div class="col-md-12">
                <h4><strong>Добавить месяц</strong></h4>
            </div>
            <?php $form = ActiveForm::begin([
                'id' => 'create-month-user-form',
                'action' => ['create-month-user', 'userId' => $model->id],
                'class' => 'form-inline',
            ]); ?>
            <div class="form-group col-md-6">
                <?php
                    $months = Months::find()
                        ->joinWith('course')
                        ->all();

                    foreach ($months as $month) {
                        $month->name = $month->course->name." ".$month->name;
                        $month->courseId = $month->course->name;
                    }
                ?>
                <?= Select2::widget([
                    'id' => 'month',
                    'name' => 'month',
                    'data' => ArrayHelper::map($months, 'id', 'name', 'courseId'),
                    'options' => [
                        'placeholder' => 'Выберите курс...',
                    ],
                ]) ?>
            </div>
            <div class="form-group col-md-4">
                <?= DepDrop::widget([
                    'id' => 'subType',
                    'name' => 'subscriptionType',
                    'options' => ['placeholder' => 'Тип абонемента...'],
                    'type' => DepDrop::TYPE_SELECT2,
                    'select2Options' => ['pluginOptions' => ['allowClear' => true]],
                    'pluginOptions' => [
                        'depends' => ['month'],
                        'url' => Url::to(['check-user-stream', 'userId' => $model->id]),
                        'loadingText' => 'Загрузка данных...',
                    ],
                    'pluginEvents' => [
                        "depdrop:change"=>"function(event, id, value, count) {
                                let data = $(this).select2('data');
                                if(data.length > 0)
                                    if(data[0].id != 'forbidden' && data[0].id != '')
                                        $('#addUserButton').attr('disabled', false);
                            }",
                        "change" => "function() {
                                let data = $(this).select2('data');
                                if(data.length > 0)
                                    if(data[0].id != 'forbidden' && data[0].id != '')
                                        $('#addUserButton').attr('disabled', false);
                            }",
                    ],
                ]);
                ?>
            </div>
            <div class="form-group col-md-2">
                <?= Html::submitButton(Yii::t('app', 'Добавить'), [
                    'class' => 'btn btn-success btn-block',
                    'id' => 'addUserButton',
                    'disabled' => true,
                ]) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <?php Pjax::begin(['id' => 'user-months']); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            [
                'attribute' => 'course',
                'value' => 'month.course.name',
                'label' => 'Курс',
            ],
            [
                'attribute' => 'Месяц',
                'value' => 'month.name',
                'label' => 'Месяц',
            ],
            [
                'attribute' => 'Тип абонемента',
                'value' => function($model)
                {
                    if(!$model->month->course->isSpec)
                    {
                        if($model->month->id != $model->stream->monthId)
                        {
                            $isNew = false;
                        }
                        else
                        {
                            $isNew = true;
                        }

                        $type = $model->stream->type;

                        if(!$isNew && AppController::isSubscription($type))
                            $stream = AppController::castSubType($type);
                        else
                            $stream = $type;

                        $value = $model->streamId != null ? $stream : ($model->isDemo ? AppController::STREAM_TYPE_DEMO_MONTH : AppController::STREAM_TYPE_MONTH);
                    }
                    else
                    {
                        $value = AppController::STREAM_TYPE_SPEC;
                    }

                    return AppController::getStreamType($value);
                },
                'label' => 'Тип абонемента',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',

                'urlCreator' => function ($action, $model, $key, $index) use ($user) {
                    $url = Url::to(['months/'.$action.'-month-user', 'userId' => $user->id, 'courseId' => $model->courseId, 'monthId' => $model->monthId]);
                    return $url;
                },

                'buttons' => [
                    'update' => function ($url, $model) use ($user) {
                        return Html::button('',
                            [
                                'title' => Yii::t('app', 'Редактировать'),
                                'class' => 'btn btn-default glyphicon glyphicon-pencil showModalButton',
                                'data-toggle'=>'modal',
                                'data-target'=>'#updateMonthUser',
                                'name' => $user->name,
                                'href' => $url,
                            ]
                        );
                    },
                    'delete' => function ($url, $model) use ($month) {
                        return false;
                        if(Yii::$app->user->identity->isTeacher())
                            return false;
                        $isNew = true;
                        if(!$month->course->isSpec)
                            if(!empty($model->streams))
                                if($model->streams[0]->monthId != $month->id)
                                    $isNew = false;

                        foreach ($model->boughtCourses as $boughtCourse)
                            if($boughtCourse->monthId == $month->id)
                                $currentBC = $boughtCourse;

                        $isGifted = $currentBC->giftedByMonthId != null;

                        if($currentBC->streamId != null)
                            $isSubscription = true;
                        else
                            $isSubscription = false;

                        $isUndeletable = false;
                        if(!$isNew)
                        {
                            if($isGifted)
                            {
                                $isUndeletable = true;
                                $reason = 'Нельзя удалить подарок';
                            }

                            if($isSubscription && !$month->course->isSpec)
                            {
                                $isUndeletable = true;
                                $reason = 'Нельзя удалить продолжение абонемента';
                            }
                        }
                        else
                        {
                            $allCourseMonths = [];
                            foreach ($model->boughtCourses as $boughtCourse)
                                if($boughtCourse->giftedByMonthId == null)
                                    array_push($allCourseMonths, $boughtCourse);

                            $streamMonths = [];
                            foreach ($model->boughtCourses as $boughtCourse)
                                if($boughtCourse->streamId != null)
                                    array_push($streamMonths, $boughtCourse);

                            $diff = array_diff(ArrayHelper::getColumn($allCourseMonths, 'id'), ArrayHelper::getColumn($streamMonths, 'id'));
                            if(!empty($diff))
                            {
                                $isUndeletable = true;
                                $reason = 'Сначала удалите продления в других месяцах этого курса';
                            }
                        }

                        return Html::button('', [
                            'onclick' => '
                                if(confirm("Пользователь месяца удалится без возможности востановления.\nПродолжить?"))
                                    $.ajax({
                                        type: "POST",
                                        url: "'.Url::to(['delete-month-user', 'userId' => $model->id, 'monthId' => $month->id, 'new' => ($isNew ? 'true' : 'false')]).'",
                                    })
                                    .done(function(data) {
                                        $.pjax.reload({container: "#month-users"});
                                        $("#updateMonthUser").modal("hide");
                                });',
                            'class' => 'btn btn-danger glyphicon glyphicon-trash',
                            'disabled' => $isUndeletable,
                            'title' => $isUndeletable ? $reason : 'Удалить',
                        ]);
                    }
                ]
            ]
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>

<?php
yii\bootstrap\Modal::begin([
    'headerOptions' => ['id' => 'modalHeader'],
    'id' => 'updateMonthUser',
    'size' => 'modal-lg',
]);
echo "<div id='modalContent'></div>";
yii\bootstrap\Modal::end();
?>

<?php
$formJS = <<<JS

    $('#create-month-user-form').on('beforeSubmit', function () {
        var yiiform = $(this);
        $.ajax({
                type: yiiform.attr('method'),
                url: yiiform.attr('action'),
                data: yiiform.serializeArray()
            }
        )
        .done(function(data) {
           if(data.success) {
               $('#user').val(null).trigger('change');
               $('#subType').val(null).trigger('change');
               $('#subType').attr('disabled', true);
               $('#addUserButton').attr('disabled', true);
               $.pjax.reload({container: '#user-months',  async: false});
            }
        })
    return false;
    });
JS;

$this->registerJs($formJS);