<?php
/* @var $this yii\web\View */
/* @var $model \app\models\Courses */
/* @var \chumakovanton\tinkoffPay\TinkoffPay $paymentService */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "Купить курс ".$course->name;
?>

<?php

if ($type == 'course')
{
    $amount = $course->price();
    $month = $course->currentMonth();
    echo    "<div class=\"jumbotron half\">"
                ."<h2>Вы покупаете месяц \"$month->name\" курса \"$course->name\"</h2>"
                ."<h4>Стоимость курса ".$amount." рублей.</h4>"
                ."<hr>"
                ."<p>"
                .Html::a('Оплатить курс', \yii\helpers\Url::to(['/pay/buy']),
                    [
                        'data' => [
                            'method' => 'post',
                            'params' => ['course' => $course->id, 'month' => $month->id, 'type' => 'course', 'amount' => $amount],
                        ],
                        'class' => 'btn btn-primary btn-block btn-lg',
                    ]
                )
                ."</p>
            </div>";
}
elseif ($type == 'short')
{
    echo    "<div class=\"jumbotron half\">"
                ."<h2>Вы покупаете 3х месячный абонемент курса \"$course->name\"</h2>"
                ."<h4>Стоимость абонемента ".$course->price('short')." рублей.</h4>"
                ."<hr>"
                ."<p>"
                .Html::a('Оплатить абонемент', \yii\helpers\Url::to(['/pay/buy']),
                    [
                        'data' => [
                            'method' => 'post',
                            'params' => ['course' => $course->id, 'type' => 'short'],
                        ],
                        'class' => 'btn btn-primary btn-block btn-lg',
                    ]
                )
                ."</p>
            </div>";
}
elseif ($type == 'long')
{
    $amount = $course->price('long');
    echo    "<div class=\"jumbotron half\">"
                ."<h2>Вы покупаете годовой абонемент курса \"$course->name\"</h2>"
                ."<h4>Стоимость абонемента ".$amount." рублей.</h4>"
                ."<hr>"
                ."<p>"
                .Html::a('Оплатить абонемент', \yii\helpers\Url::to(['/pay/buy']),
                    [
                        'data' => [
                            'method' => 'post',
                            'params' => ['course' => $course->id, 'month' => $course->currentMonth()->id, 'type' => 'long', 'amount' => $amount],
                        ],
                        'class' => 'btn btn-primary btn-block btn-lg',
                    ]
                )
                ."</p>
            </div>";
}
elseif ($type == 'month')
{
    $amount = $course->price('month');
    echo    "<div class=\"jumbotron half\">"
                ."<h2>Вы покупаете месяц \"$month->name\" курса \"$course->name\"</h2>"
                ."<h4>Стоимость раздела ".$amount." рублей.</h4>"
                ."<hr>"
                ."<p>"
                .Html::a('Оплатить раздел', \yii\helpers\Url::to(['/pay/buy']),
                    [
                        'data' => [
                            'method' => 'post',
                            'params' => ['course' => $course->id, 'month' => $month->id, 'type' => 'month', 'amount' => $amount],
                        ],
                        'class' => 'btn btn-primary btn-block btn-lg',
                    ]
                )
                ."</p>
            </div>";
}
elseif ($type == 'spec')
{
    $amount = $course->price('spec');
    echo    "<div class=\"jumbotron half\">"
                ."<h2>Вы покупаете спецкурс \"$course->name\"</h2>"
                ."<h4>Стоимость спецкурса ".$amount." рублей.</h4>"
                ."<hr>"
                ."<p>"
                .Html::a('Оплатить спецкурс', \yii\helpers\Url::to(['/pay/buy']),
                    [
                        'data' => [
                            'method' => 'post',
                            'params' => ['course' => $course->id, 'month' => $course->specMonth()->id, 'type' => 'spec', 'amount' => $amount],
                        ],
                        'class' => 'btn btn-primary btn-block btn-lg',
                    ]
                )
                ."</p>
            </div>";
}











//try {
//    $response = $paymentRequest->send();
//} catch (\chumakovanton\tinkoffPay\exceptions\HttpException $exception) {
//    throw new \yii\web\HttpException($exception->statusCode, $exception->getMessage());
//}
//
//$paymentUrl = $response->getPaymentUrl();
//echo $paymentUrl;