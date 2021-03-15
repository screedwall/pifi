<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use kartik\select2\Select2;
use yii\widgets\Pjax;
use yii\web\JsExpression;
use app\controllers\AppController;
use yii\bootstrap\ActiveForm;
use kartik\depdrop\DepDrop;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Months */
/* @var $courseId int */
/* @var $searchModel app\models\MonthUsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$month = $model;

$this->title = Yii::t('app', 'Update Months: {name}', [
    'name' => $model->name,
]);

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Courses'), 'url' => ['courses/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', $model->course->name), 'url' => ['courses/update', 'id' => $model->courseId, '#' => 'months']];
$this->params['breadcrumbs'][] = Yii::t('app', $model->name);

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
          });
    });
JS;

$this->registerJs($JS);
?>
<div class="months-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model
    ]) ?>

</div>

<div class="month-users" id="users">
    <h2>Пользователи месяца</h2>
    <div class="create-month-user">
        <div class="row">
            <div class="col-md-12">
                <h4><strong>Добавить пользователя</strong></h4>
            </div>
            <?php $form = ActiveForm::begin([
                'id' => 'create-month-user-form',
                'action' => ['create-month-user', 'courseId' => $model->courseId, 'monthId' => $model->id],
                'class' => 'form-inline',
            ]); ?>
                <div class="form-group col-md-6">
                    <?= Select2::widget([
                        'name' => 'user',
                        'id' => 'user',
                        'options' => [
                            'placeholder' => 'Выберите пользователя...',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'minimumInputLength' => 3,
                            'ajax' => [
                                'url' => \yii\helpers\Url::to(['users/list']),
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {q:params.term}; }'),
                                'delay' => 350,
                            ],
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(user) { return user.text; }'),
                            'templateSelection' => new JsExpression('function(user) { return user.text; }'),
                        ],
                        'pluginEvents' => [
                            "change" => "function() {
                                $('#addUserButton').attr('disabled', true);
                            }",
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
                            'depends' => ['user'],
                            'url' => Url::to(['check-user-stream', 'courseId' => $model->courseId, 'monthId' => $model->id]),
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
    <br>
    <h4><strong>Список пользователей</strong></h4>
    <?php
        Pjax::begin(['id' => 'month-users']);
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                    'name',
                    'vk',
                ['class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {delete}',

                    'urlCreator' => function ($action, $model, $key, $index) use ($month) {
                        $url = Url::to([$action.'-month-user', 'userId' => $model->id, 'courseId' => $month->courseId, 'monthId' => $month->id]);
                        return $url;
                    },

                    'buttons' => [
                        'update' => function ($url, $model) {
                            return Html::button('',
                                        [
                                            'title' => Yii::t('app', 'Редактировать'),
                                            'class' => 'btn btn-default glyphicon glyphicon-pencil showModalButton',
                                            'data-toggle'=>'modal',
                                            'data-target'=>'#updateMonthUser',
                                            'name' => $model->name,
                                            'href' => $url,
                                        ]
                                    );
                        },
                        'delete' => function ($url, $model) use ($month) {
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
                                if(!empty($diff) && !$month->course->isSpec)
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
                                        $.pjax.reload({container: "#user-months",  async: false});
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
        ]);
        Pjax::end();
    ?>
</div>

<div class="lessons-index">
    <h1>Уроки</h1>
    <p>
        <?= Yii::$app->user->identity->isAdmin() ? Html::a(Yii::t('app', 'Create Lessons'), Url::to(['lessons/create', 'courseId' => $courseId, 'monthId'=>$model->id]), ['class' => 'btn btn-success']) : '' ?>
    </p>

    <?php
    echo GridView::widget([
        'dataProvider' => new \yii\data\ActiveDataProvider([
            'query' => $model->getLessons(),
        ]),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'name',
//            'shortDescription',
//            'description:ntext',
//            'video',
            'lessonDate',
            'homeworkDate',
//            'month',
            //'course',

            ['class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('app', 'Просмотр'),
                        ]);
                    },

                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('app', 'Редактировать'),
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        if(Yii::$app->user->identity->isTeacher())
                            return false;
                        return Html::a('', $url,
                            [
                                'title' => Yii::t('app', 'Удалить'),
                                'data' => [
                                    'method' => 'post',
                                    'confirm' => "Урок удалится без возможности востановления.\nПродолжить?",
                                ],
                                'class' => 'glyphicon glyphicon-trash'
                            ]
                        );
                    }
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if($action==='view'){
                        $url = Url::to(['/lessons/view', 'id' => $model->id]);
                        return $url;
                    }
                    $url = Url::to(['lessons/'.$action, 'id' => $model->id, 'courseId' => Yii::$app->request->get('courseId'), 'monthId' => Yii::$app->request->get('id')]);
                    return $url;
                }
            ],
        ],
    ]); ?>

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
