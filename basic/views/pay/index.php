<?php
/* @var $this yii\web\View */
/* @var $model \app\models\Courses */
/* @var \chumakovanton\tinkoffPay\TinkoffPay $paymentService */
$this->title = "Купить курс ".$model->name;
?>

<h1><?= $model->name ?></h1>

<p>
    Оплата курса
    <br>
    Сумма: <?= $model->price ?> рублей.
</p>

<?php
$paymentService = Yii::$app->tinkoffPay;

$paymentRequest = new \chumakovanton\tinkoffPay\request\RequestInit('order1', 10);

$paymentRequest->addData('user_id', '123');

$paymentResponse = $paymentService->initPay($paymentRequest);

echo print_r($paymentResponse);
//try {
//    $response = $paymentRequest->send();
//} catch (\chumakovanton\tinkoffPay\exceptions\HttpException $exception) {
//    throw new \yii\web\HttpException($exception->statusCode, $exception->getMessage());
//}
//
//$paymentUrl = $response->getPaymentUrl();
//echo $paymentUrl;