<?php
/* @var $this yii\web\View */
/* @var $model \app\models\Courses */
/* @var \chumakovanton\tinkoffPay\TinkoffPay $paymentService */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "Купить курс ".$course->name;
?>

<?php

$paymentService = Yii::$app->tinkoffPay;
$paymentRequest = new \chumakovanton\tinkoffPay\request\RequestInit('25', 100);
//$paymentRequest->addData('OrderId', '2');
//$paymentResponse = $paymentService->initPay($paymentRequest);

//echo print_r($paymentResponse);

if ($type == 'course')
{

    echo    "<div class=\"jumbotron half\">"
                ."<h2>Вы покупаете курс \"$course->name\"</h2>"
                ."<h4>Стоимость курса ".$course->price()." рублей.</h4>"
                ."<hr>"
                ."<p>"
                .Html::a('Оплатить курс', \yii\helpers\Url::to(['/pay/buy']),
                    [
                        'data' => [
                            'method' => 'post',
                            'params' => ['course' => $course->id, 'type' => 'course'],
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
    echo    "<div class=\"jumbotron half\">"
                ."<h2>Вы покупаете годовой абонемент курса \"$course->name\"</h2>"
                ."<h4>Стоимость абонемента ".$course->price('long')." рублей.</h4>"
                ."<hr>"
                ."<p>"
                .Html::a('Оплатить абонемент', \yii\helpers\Url::to(['/pay/buy']),
                    [
                        'data' => [
                            'method' => 'post',
                            'params' => ['course' => $course->id, 'type' => 'long'],
                        ],
                        'class' => 'btn btn-primary btn-block btn-lg',
                    ]
                )
                ."</p>
            </div>";
}
elseif ($type == 'month')
{
    echo    "<div class=\"jumbotron half\">"
                ."<h2>Вы покупаете раздел \"$month->name\" курса \"$course->name\"</h2>"
                ."<h4>Стоимость раздела ".$course->price('month')." рублей.</h4>"
                ."<hr>"
                ."<p>"
                .Html::a('Оплатить раздел', \yii\helpers\Url::to(['/pay/buy']),
                    [
                        'data' => [
                            'method' => 'post',
                            'params' => ['course' => $course->id, 'type' => 'month', 'month' => $month->id],
                        ],
                        'class' => 'btn btn-primary btn-block btn-lg',
                    ]
                )
                ."</p>
            </div>";
}
elseif ($type == 'spec')
{
    echo    "<div class=\"jumbotron half\">"
                ."<h2>Вы покупаете спецкурс \"$course->name\"</h2>"
                ."<h4>Стоимость спецкурса ".$course->price('spec')." рублей.</h4>"
                ."<hr>"
                ."<p>"
                .Html::a('Оплатить спецкурс', \yii\helpers\Url::to(['/pay/buy']),
                    [
                        'data' => [
                            'method' => 'post',
                            'params' => ['course' => $course->id, 'type' => 'month', 'month' => $month->id],
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