<?php
/* @var $this yii\web\View */
/* @var $model \app\models\Courses */
/* @var \chumakovanton\tinkoffPay\TinkoffPay $paymentService */

use yii\bootstrap\Html;
use yii\helpers\Url;

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
    case 'course':
        $firstLine = "<h2>Вы покупаете месяц \"$month->name\" курса \"$course->name\"</h2>";
        $secondLine = "<h4>Стоимость курса ".$amount." рублей.</h4>";
        $buttonName = 'Оплатить месяц';
        break;
    case 'short':
        $firstLine = "<h2>Вы покупаете 3х месячный абонемент курса \"$course->name\"</h2>";
        $secondLine = "<h4>Стоимость абонемента ".$course->price('short')." рублей.</h4>";
        $buttonName = 'Оплатить абонемент';
        break;
    case 'long':
        $firstLine = "<h2>Вы покупаете годовой абонемент курса \"$course->name\"</h2>";
        $secondLine = "<h4>Стоимость абонемента ".$amount." рублей.</h4>";
        $buttonName = 'Оплатить абонемент';
        break;
    case 'month':
        $firstLine = "<h2>Вы покупаете месяц \"$month->name\" курса \"$course->name\"</h2>";
        $secondLine = "<h4>Стоимость месяца ".$amount." рублей.</h4>";
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