<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\controllers\AppController;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TinkoffPaySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Payments');
$this->params['breadcrumbs'][] = $this->title;

$types = [];
foreach (AppController::STREAM_TYPES as $STREAM_TYPE)
    if($STREAM_TYPE != AppController::STREAM_TYPE_SHORT_CONT && $STREAM_TYPE != AppController::STREAM_TYPE_LONG_CONT)
        $types += [$STREAM_TYPE => AppController::getStreamType($STREAM_TYPE)];

$JS = <<<JS
    $(function(){
          $(document).on('click', '.showModalButton', function(){
            if ($('#payInfo').data('bs.modal').isShown) {
                $('#payInfo').find('#modalContent')
                        .load($(this).attr('href'));
                document.getElementById('modalHeader').innerHTML 
                      = '<button type="button" class="close" data-dismiss="modal">&times;</button>'
                      + '<h4>' + $(this).attr('name') + '</h4>';
            } else {
                $('#payInfo').modal('show')
                        .find('#modalContent')
                        .load($(this).attr('href'));
                document.getElementById('modalHeader').innerHTML
                      = '<button type="button" class="close" data-dismiss="modal">&times;</button>'
                      + '<h4>' + $(this).attr('name') + '</h4>';
            }
        });
          $('#payInfo').on('hidden.bs.modal', function (e) {
              $('#modalHeader').html('<h4>Загрузка...</h4>');
              $('#modalContent').html('<div style="text-align:center"><img class="load-gif" src="/img/load.gif"></div>');
          });
    });
JS;

$this->registerJs($JS);

?>

<div class="payments-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            [
                'attribute' => 'user',
                'value' => 'user.name',
                'label' => 'Пользователь',
            ],
            [
                'attribute' => 'month',
                'label' => 'Месяц',
                'content' => function($model){
                    return $model->course->name." ".$model->month->name;
                }
            ],
            'amount',
            [
                'attribute' => 'type',
                'content' => function($model){
                    return \app\controllers\AppController::getStreamType($model->type);
                },
                'filter' => $types,
            ],
//            [
//                'attribute' => 'createdAt',
//                'filter' => \kartik\date\DatePicker::widget([
//                    'name' => 'TinkoffPaySearch[createdAt]',
//                    'value' => Yii::$app->request->get('TinkoffPaySearch')['createdAt'],
//                    'options' => ['autocomplete' => 'off'],
//                    'type' => \kartik\date\DatePicker::TYPE_COMPONENT_APPEND,
//                    'pickerButton' => false,
//                    'pluginOptions' => [
//                        'autoclose' => true,
//                        'format' => 'dd.mm.yyyy',
//                    ],
//                ]),
//                'format' => 'html',
//            ],
            [
                'attribute' => 'status',
                'content' => function($model){
                    switch($model->status)
                    {
                        case 'NEW':
                            return 'Создан';
                        case 'CONFIRMED':
                            return 'Оплачен';
                        default:
                            return 'Неизвестный статус';
                    }
                },
                'filter' => [
                    'NEW' => 'Создан',
                    'CONFIRMED' => 'Оплачен',
                ]
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{info}',
                'buttons' => [
                    'info' => function ($url, $model) {
                        return Html::button('',
                            [
                                'title' => Yii::t('app', 'Редактировать'),
                                'class' => 'btn glyphicon glyphicon-pencil showModalButton '.(empty($model->response) ? 'btn-danger' : 'btn-default'),
                                'data-toggle'=>'modal',
                                'data-target'=>'#payInfo',
                                'name' => "Оплата #".$model->id,
                                'href' => \yii\helpers\Url::to(['pay/info', 'id' => $model->id]),
                                'disabled' => empty($model->response),
                                'title' => empty($model->response) ? 'Нет информации' : null,
                            ]
                        );
                    },
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>
</div>


<?php
    yii\bootstrap\Modal::begin([
        'headerOptions' => ['id' => 'modalHeader'],
        'id' => 'payInfo',
        'size' => 'modal-lg',
    ]);
    echo "<div id='modalContent'></div>";
    yii\bootstrap\Modal::end();
?>