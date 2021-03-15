<?php
/* @var $this yii\web\View */
/* @var $model \app\models\Courses */
/* @var $course \app\models\Courses */
/* @var $amount int */
/* @var $type string */
/* @var \chumakovanton\tinkoffPay\TinkoffPay $paymentService */

use yii\bootstrap\Html;
use yii\helpers\Url;
use app\controllers\AppController;

$this->title = "Купить курс ".$course->name;
?>

<?php

if ($bought)
{
    echo    "<div class=\"jumbotron half\">"
        ."<h2>Вы уже купили этот месяц.</h2>"
        ."<h4>Вы можете посмотреть его в вашем ".Html::a('профиле', \yii\helpers\Url::to(['/profile'])).".</h4>"
        ."<hr>"
        ."<p>"
        ."</p>
            </div>";
    return;
}

switch ($type){
    case AppController::STREAM_TYPE_SPEC:
        $firstLine = "<h2>Вы покупаете раздел \"$month->name\" спецкурса \"$course->name\"</h2>";
        $secondLine = "<h4>Стоимость раздела ".$amount." рублей.</h4>";
        $buttonName = 'Оплатить раздел';
        break;
    case AppController::STREAM_TYPE_COURSE:
        $firstLine = "<h2>Вы покупаете месяц \"$month->name\" курса \"$course->name\"</h2>";
        $secondLine = "<h4>Стоимость курса ".$amount." рублей.</h4>";
        $buttonName = 'Оплатить месяц';
        break;
    case AppController::STREAM_TYPE_EXTRA_SHORT:
        $firstLine = "<h2>Вы покупаете 2х месячный абонемент курса \"$course->name\"</h2>";
        $secondLine = "<h4>Стоимость абонемента ".$amount." рублей.</h4>";
        $buttonName = 'Оплатить абонемент';
        break;
    case AppController::STREAM_TYPE_SHORT:
        $firstLine = "<h2>Вы покупаете 3х месячный абонемент курса \"$course->name\"</h2>";
        $secondLine = "<h4>Стоимость абонемента ".$amount." рублей.</h4>";
        $buttonName = 'Оплатить абонемент';
        break;
    case AppController::STREAM_TYPE_LONG:
        $firstLine = "<h2>Вы покупаете годовой абонемент курса \"$course->name\"</h2>";
        $secondLine = "<h4>Стоимость абонемента ".$amount." рублей.</h4>";
        $buttonName = 'Оплатить абонемент';
        break;
    case AppController::STREAM_TYPE_DEMO_MONTH:
    case AppController::STREAM_TYPE_DEMO:
        $firstLine = "<h2>Вы покупаете демо-месяц \"$month->name\" курса \"$course->name\"</h2>";
        $secondLine = "<h4>Стоимость демо-месяца ".$amount." рублей.</h4>";
        $buttonName = 'Оплатить месяц';
        break;
    case AppController::STREAM_TYPE_MONTH:
        $firstLine = "<h2>Вы покупаете месяц \"$month->name\" курса \"$course->name\"</h2>";
        $secondLine = "<h4>Стоимость месяца ".$amount." рублей.</h4>";
        $buttonName = 'Оплатить месяц';
        break;
    case AppController::STREAM_TYPE_DEMO_CONTINUATION:
        $firstLine = "<h2>Вы покупаете продление демо-месяца \"$month->name\" курса \"$course->name\"</h2>";
        $secondLine = "<h4>Стоимость продления демо-месяца ".$amount." рублей.</h4>";
        $buttonName = 'Оплатить месяц';
        break;

}


\yii\widgets\Pjax::begin(['enablePushState' => false]);
echo    "<div class=\"jumbotron half\">"
            .$firstLine
            .$secondLine
            ."<hr>"
            ."<p>"
            .Html::a($buttonName, \yii\helpers\Url::to(['/pay/buy']),
                [
                    'data' => [
                        'method' => 'post',
                        'params' => ['course' => $course->id, 'month' => $month->id, 'type' => $type, 'amount' => $amount, 'coupon' => $coupon],
                    ],
                    'data-ajax' => 0,
                    'class' => 'btn btn-primary btn-block btn-lg',
                ]
            )
            ."</p>";

if(!empty($coupon))
    echo "<h4>Промокод активирован!</h4>";
else
{
    echo Html::beginForm(['pay/index'], 'post', ['data-pjax' => '1','enctype' => 'multipart/form-data']);
    echo '<div class="form-group">'
        .Html::label('Есть промокод? Впиши его ниже!', 'promo')
        .\yii\bootstrap\Html::textInput('coupon', '', [
            'class' => "form-control",
        ])
        ."</div>";
    echo Html::a("Применить", ["pay/index"], [
        'data' => [
            'method' => 'post',
            'params' => ['course' => $course->id, 'month' => $month->id, 'type' => $type, 'amount' => $amount],
        ],
        'class' => 'btn btn-success btn-block btn-lg'
    ]);
    echo Html::endForm();
}
if(isset($failCoupon))
    echo "<h4>Код купона введен неверно!</h4>";

echo "</div>";
\yii\widgets\Pjax::end();