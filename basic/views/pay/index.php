<?php
/* @var $this yii\web\View */
/* @var $model \app\models\Courses */
/* @var \chumakovanton\tinkoffPay\TinkoffPay $paymentService */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "Купить курс ".$model->name;
?>

<?php
/*
$paymentService = Yii::$app->tinkoffPay;

$paymentRequest = new \chumakovanton\tinkoffPay\request\RequestInit('25', 100);

$paymentRequest->addData('user_id', '123');

$paymentResponse = $paymentService->initPay($paymentRequest);

echo print_r($paymentResponse);*/

if (!empty($months))
{
    $hasMany = count($months) > 1;
    $monthstr = "";
    $price = 0;
    if(count($months) > 1){
        $i = 0;
        foreach ($months as $month){
            if($i == count($months) - 1)
                $monthstr = $monthstr."\"".$month->name."\"";
            else
                $monthstr = $monthstr."\"".$month->name."\"".", ";
            $price += $month->price;
            $i++;
        }
    }
    else
    {
        $monthstr = "\"".$months[0]->name."\"";
        $price = $months[0]->price;
    }

    echo    "<div class=\"jumbotron half\">"
                ."<h2>Вы покупаете раздел".($hasMany ? "ы" : null)." $monthstr курса \"$course->name\"</h2>"
                .($hasMany ? "<h4>Стоимость покупаемых разделов $price рублей.</h4>" : "<h4>Стоимость раздела $price рублей.</h4>")
                ."<p><a class=\"btn btn-primary btn-block btn-lg\" href=\"#\" role=\"button\">Оплатить раздел".($hasMany ? "ы" : null)."</a></p>"
                ."<hr>"
                ."<h4>А вы знали, что выгоднее "
                .Html::a('покупать курс',\yii\helpers\Url::to(['/pay', 'course' => $course->id]))
                ." целиком?</h4>"
                ."<h4>Он стоит всего $course->price рублей.</h4>"
            ."</div>";
}
elseif (!empty($course))
{
    echo    "<div class=\"jumbotron half\">
              <h2>Вы покупаете курс \"$course->name\" целиком</h2>
              <h2>Супер!</h2>"
              ."<h4>Стоимость курса $course->price рублей.</h4>
              <hr>
              <p><a class=\"btn btn-primary btn-block btn-lg\" href=\"#\" role=\"button\">Оплатить курс</a></p>
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