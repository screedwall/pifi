<?php

namespace app\controllers;

use app\models\BoughtCourses;
use app\models\Courses;
use app\models\Months;
use app\models\Tinkoffpay;
use app\models\UsersStream;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class PayController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $request = \Yii::$app->request;

        $getCourse = $request->get('course');
        $getType = $request->get('type');
        $getMonth = $request->get('month');
        $month = Months::findOne(['id' => $getMonth]);

        $error = false;

        if(!empty($getCourse))
        {
            $course = \app\models\Courses::findOne(['id' => $getCourse]);
            $error = $course == null;
        }
        else
            $error = true;

        if(empty($getType))
            $error = true;

        if(!$error)
            return $this->render('index', [
                'course' => $course,
                'type' => $getType,
                'month' => $month,
            ]);
        else
            throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionBuy()
    {
        $courseId = \Yii::$app->request->post('course');
        $monthId = \Yii::$app->request->post('month');
        $type = \Yii::$app->request->post('type');
        $amount = \Yii::$app->request->post('amount');
        $userId = \Yii::$app->user->identity->getId();

        $payment = new TinkoffPay();
        $payment->amount = $amount;
        $payment->status = "NEW";
        $payment->createdAt = date('d.m.Y H:i:s');
        $payment->courseId = $courseId;
        $payment->monthId = $monthId;
        $payment->userId = $userId;
        $payment->type = $type;
        $payment->save();

        $paymentService = \Yii::$app->tinkoffPay;
        $paymentRequest = new \chumakovanton\tinkoffPay\request\RequestInit($payment->id, $amount*100);
        $paymentResponse = $paymentService->initPay($paymentRequest);

        if($paymentResponse->getStatus() == "NEW" && $paymentResponse->getSuccess())
        {
            return $this->redirect($paymentResponse->getPaymentUrl());
        }
    }

    public function actionSuccess()
    {
        $productjson = json_encode(\Yii::$app->request->post());
        $jsonfile = \Yii::getAlias('@webroot/Tinkoff.json');
        $fp = fopen($jsonfile, 'a+');
        fwrite($fp, $productjson."\r\n ========\r\n");
        fclose($fp);

//        $course = Courses::findOne(['id' => $courseId]);
//        $getMonth = Months::findOne(['id' => $monthId]);
//
//        $type = \Yii::$app->request->post('type');
//        $userId = \Yii::$app->user->identity->getId();
//        $currentMonth = $course->currentMonth();
//        $error = false;
//        $months = [];
//
//        if($type == 'course')
//        {
//            array_push($months, $course->currentMonth());
//        }
//        elseif ($type == 'short')
//        {
//            $_months = $course->getMonths()->orderBy('dateFrom')->all();
//        }
//        elseif ($type == 'long')
//        {
//            $months = $course->months;
//        }
//        elseif ($type == 'month')
//        {
//            array_push($months, $getMonth);
//        }
//
//        foreach ($months as $month)
//        {
//            $boughtCourse = new BoughtCourses();
//            $boughtCourse->userId = $userId;
//            $boughtCourse->monthId = $month->id;
//            $boughtCourse->courseId = $course->id;
//            $boughtCourse->save();
//
////            foreach ($month->gifts as $gift)
////            {
////                $boughtCourse = new BoughtCourses();
////                $boughtCourse->userId = $userId;
////                $boughtCourse->monthId = $gift->id;
////                $boughtCourse->courseId = $course->id;
////                $boughtCourse->save();
////            }
//        }
//
//        if($type != 'month')
//        {
//            $stream = new UsersStream();
//            $stream->userId = $userId;
//            $stream->courseId = $course->id;
//            $stream->monthId = $currentMonth->id;
//            $stream->type = $type;
//            $stream->boughtId = $boughtCourse->id;
//            $stream->save();
//        }
//
//        return $this->redirect(['/profile']);
    }

}
