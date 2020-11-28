<?php

namespace app\controllers;

use app\models\BoughtCourses;
use app\models\Coupons;
use app\models\Courses;
use app\models\Months;
use app\models\TinkoffPay;
use app\models\Users;
use app\models\UsersStream;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class PayController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'buy'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if ($action->id == 'success')
            $this->enableCsrfValidation = false;

        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $error = false;
        $bought = false;

        $request = \Yii::$app->request;

        if(\Yii::$app->request->isAjax)
        {
            $response = \Yii::$app->request->post();
            $response['course'] = Courses::findOne(['id' => $response['course']]);
            $response['month'] = Months::findOne(['id' => $response['month']]);
            $response['bought'] = false;
            $response['coupon'] = null;

            $coupon = Coupons::find()
                ->where(['code' => \Yii::$app->request->post('coupon')])
                ->andWhere(['>', 'rest', 0])
                ->one();
            if(!empty($coupon))
            {
                $response['coupon'] = $coupon->id;
                $response['amount'] = $response['amount'] - $coupon->discount;
            }
            else
            {
                $response['failCoupon'] = true;
            }

            return $this->render('index', $response);
        }

        $getCourse = $request->get('course');
        $getType = $request->get('type');
        $getMonth = $request->get('month');

        if(!empty($getCourse))
            $course = Courses::findOne(['id' => $getCourse]);
        else
            $error = true;

        if(!$error){
            $monthQuery = null;
            switch ($getType) {
                case 'course':
                    break;
                case 'month':
                    if(!empty($getMonth))
                        $monthQuery = Months::findOne(['id' => $getMonth]);
                    else
                        $error = true;
                    break;
                case 'short':
                    break;
                case 'long':
                    break;
                case 'spec':
                    $month = $course->specMonth();
                    break;
                default:
                    $error = true;
                    break;
            }

            $month = (empty($monthQuery) ? $course->currentMonth() : $monthQuery);

            $discount = false;
            $user = \Yii::$app->user->identity;

            if(BoughtCourses::find()
                    ->where(['userId' => $user->id])
                    ->andWhere(['monthId' => $month->id])
                    ->count() > 0)
                $bought = true;

            if($bought)
                return $this->render('index', [
                    'course' => $course,
                    'bought' => $bought,
                ]);

            $stream = UsersStream::find()
                ->where(['courseId' => $course->id])
                ->andWhere(['userId' => $user->id])
                ->one();

            if(!empty($stream))
            {
                $streamDate = date_create_from_format('d.m.Y', $stream->month->dateFrom);
                $monthDate = date_create_from_format('d.m.Y', $month->dateFrom);
                if($monthDate < $streamDate)
                    $discount = true;
            }

            if(!empty($getCourse))
            {
                if($course == null)
                    $error = true;
            }
            else
                $error = true;

            if(empty($getType))
                $error = true;

            if(!$error)
            {
                $amount = ($discount ? $course->price($getType)/2 : $course->price($getType));
                return $this->render('index', [
                    'course' => $course,
                    'type' => $getType,
                    'month' => $month,
                    'bought' => $bought,
                    'discount' => $discount,
                    'amount' => $amount,
                    'coupon' => null,
                ]);
            }
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionBuy()
    {
        $courseId = \Yii::$app->request->post('course');
        $monthId = \Yii::$app->request->post('month');
        $type = \Yii::$app->request->post('type');
        $amount = \Yii::$app->request->post('amount');
        $couponId = \Yii::$app->request->post('coupon');
        $userId = \Yii::$app->user->identity->getId();

        if(!empty($couponId))
        {
            $coupon = Coupons::findOne(['id' => $couponId]);
            $coupon->rest--;
            $coupon->save();
        }

        $payment = new \app\models\TinkoffPay();
        $payment->amount = $amount;
        $payment->status = "NEW";
        $payment->createdAt = date('d.m.Y H:i:s');
        $payment->courseId = $courseId;
        $payment->monthId = $monthId;
        $payment->userId = $userId;
        $payment->type = $type;
        $payment->save();
        return var_dump($payment);

        if(!empty($payment->getErrors()))
            throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));

        $paymentService = \Yii::$app->tinkoffPay;
        $paymentRequest = new \chumakovanton\tinkoffPay\request\RequestInit();
        $paymentRequest->Init($payment->id, $amount*100);

        $paymentResponse = $paymentService->initPay($paymentRequest);

        if($paymentResponse->getSuccess())
        {
            return $this->redirect($paymentResponse->getPaymentUrl());
        }
        else
        {
            $payment->delete();
            throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
        }
    }

    public function actionSuccess()
    {
        $responseObject = \Yii::$app->getRequest()->getBodyParams();
//        $productjson = "BODY: ".$body."\r\n";
//        $jsonfile = \Yii::getAlias('@webroot/Tinkoff.json');
//        $fp = fopen($jsonfile, 'a+');
//        fwrite($fp, $productjson."\r\n ========\r\n");
//        fclose($fp);

//        $body = '{"TerminalKey":"1605637861944DEMO","OrderId":"34","Success":true,"Status":"CONFIRMED","PaymentId":376057598,"ErrorCode":"0","Amount":199000,"CardId":53417669,"Pan":"430000******0777","ExpDate":"1122","Token":"b98fbc2dd48010459c5c29630c60731eb368a5cbbcf4701695b30b412a81d08f"}';




//        $responseObject = json_decode($body, true);

//        $requestToken = strval(\Yii::$app->request->getBodyParam('Token'));
//        $requestOrderId = intval(\Yii::$app->request->getBodyParam('OrderId'));
//        $requestStatus = strval(\Yii::$app->request->getBodyParam('Status'));

        $body = file_get_contents('php://input');
        $jsonObj = Json::decode($body, true);


//
//        $productjson = "DUMP: ".$responseObject["Token"]."\r\n";
//        $jsonfile = \Yii::getAlias('@webroot/Logs.html');
//        $fp = fopen($jsonfile, 'a+');
//        fwrite($fp, $productjson."\r\n ========\r\n");
//        fclose($fp);



//        $checkingToken = $requestToken;
//        $token = '';

//        $secretKey = \Yii::$app->tinkoffPay->getSecretKey();
//        $responseObject['Password'] = $secretKey;
//        ksort($responseObject);
//        foreach ($responseObject as $field) {
//            $token .= $field;
//        }
//
//        $token = hash('sha256', $token);

        //TODO: Compare tokens

        $payment = TinkoffPay::findOne(['id' => $jsonObj['OrderId']]);

        $jsonfile = \Yii::getAlias('@webroot/Logs.html');
        $productjson = "BODY: ".var_dump($payment)." \r\n";
        $fp = fopen($jsonfile, 'a+');
        fwrite($fp, $productjson."\r\n ========\r\n");
        fclose($fp);
        return var_dump($payment);

        $payment->status = $jsonObj['Status'];
        $payment->save();




        if($payment->status = "CONFIRMED") {
            $course = Courses::findOne(['id' => $payment->courseId]);
            $currentMonth = Months::findOne(['id' => $payment->monthId]);

            $type = $payment->type;
            $userId = $payment->userId;

            $user = Users::findOne(['id' => $userId]);

            $error = false;
            $remains = 0;
            $months = [];

            if ($type == 'course') {
                array_push($months, $currentMonth);
            } elseif ($type == 'short') {
                $_months = $course->months;
                $flag = false;
                $remains = 3;
                foreach ($_months as $lMonth)
                {
                    if($lMonth == $currentMonth)
                        $flag = true;

                    if($flag && $remains > 0)
                    {
                        $skip = false;
                        foreach ($user->months as $uMonth)
                            if($uMonth == $lMonth)
                            {
                                $skip = true;
                                break;
                            }
                        if($skip)
                            continue;

                        $boughtCourse = new BoughtCourses();
                        $boughtCourse->userId = $userId;
                        $boughtCourse->monthId = $lMonth->id;
                        $boughtCourse->courseId = $course->id;
                        $boughtCourse->paymentId = $payment->id;
                        $boughtCourse->isStream = true;
                        $boughtCourse->save();
                        $remains--;
                    }
                }
            } elseif ($type == 'long') {
                $_months = $course->months;
                $flag = false;
                $remains = 20;

                foreach ($_months as $lMonth)
                {
                    if($lMonth == $currentMonth)
                        $flag = true;

                    if($flag)
                    {
                        $skip = false;
                        foreach ($user->months as $uMonth)
                            if($uMonth == $lMonth)
                            {
                                $skip = true;
                                break;
                            }
                        if($skip)
                            continue;

                        $boughtCourse = new BoughtCourses();
                        $boughtCourse->userId = $userId;
                        $boughtCourse->monthId = $lMonth->id;
                        $boughtCourse->courseId = $course->id;
                        $boughtCourse->paymentId = $payment->id;
                        $boughtCourse->isStream = true;
                        $boughtCourse->save();
                        $remains--;
                    }
                }
            } elseif ($type == 'month') {
                array_push($months, $currentMonth);
            }

            if($type == "month" || $type == "spec")
                foreach ($months as $month) {
                    $skip = false;
                    foreach ($user->months as $uMonth)
                        if($uMonth == $month)
                        {
                            $skip = true;
                            break;
                        }
                    if($skip)
                        continue;

                    $boughtCourse = new BoughtCourses();
                    $boughtCourse->userId = $userId;
                    $boughtCourse->monthId = $month->id;
                    $boughtCourse->courseId = $course->id;
                    $boughtCourse->paymentId = $payment->id;
                    $boughtCourse->save();
                }

            foreach ($currentMonth->gifts as $gift)
            {
                $skip = false;
                foreach ($user->months as $uMonth)
                    if($uMonth == $gift->month)
                    {
                        $skip = true;
                        break;
                    }
                if($skip)
                    continue;

                $boughtCourse = new BoughtCourses();
                $boughtCourse->userId = $userId;
                $boughtCourse->monthId = $gift->monthId;
                $boughtCourse->courseId = $course->id;
                $boughtCourse->paymentId = $payment->id;
                $boughtCourse->save();
            }

            if ($type != 'month' || $type != 'spec') {
                $stream = new UsersStream();
                $stream->userId = $userId;
                $stream->courseId = $course->id;
                $stream->monthId = $currentMonth->id;
                $stream->type = $type;
                $stream->remains = $remains;
                $stream->save();
            }

        }

        return "OK";
    }
}
