<?php

namespace app\controllers;

use app\models\BoughtCourses;
use app\models\Coupons;
use app\models\Courses;
use app\models\GiftMonths;
use app\models\Months;
use app\models\TinkoffPay;
use app\models\Users;
use app\models\UsersStream;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
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
        {
            $course = Courses::findOne(['id' => $getCourse]);
            if($course->isSpec)
                throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
        }
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
                default:
                    $error = true;
                    break;
            }

            $month = (empty($monthQuery) ? $course->currentMonth() : $monthQuery);

            if(empty($month))
                throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));

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
                $amount = ($discount ? $month->price / 2 : $course->price($getType));
                if($amount == 0)
                    $amount = $month->price;

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

        if(!empty($payment->getErrors()))
            throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));

        $paymentService = \Yii::$app->tinkoffPay;
        $paymentRequest = new \chumakovanton\tinkoffPay\request\RequestInit();
        $paymentRequest->setDescription("https://vk.com/id".\Yii::$app->user->identity->vk);
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
        $body = \Yii::$app->getRequest()->getBodyParams();

        if(empty($body))
            throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));

        $checkingToken = $body["Token"];

        $token = '';
        unset($body["Token"]);
        $secretKey = \Yii::$app->tinkoffPay->getSecretKey();
        $body['Password'] = $secretKey;

        ksort($body);
        foreach ($body as $field) {
            if(gettype($field) == "boolean")
                $token .= $field ? "true" : "false";
            else
                $token .= strval($field);
        }

        $token = hash('sha256', $token);

        if(!YII_ENV_DEV)
            if($token != $checkingToken)
                throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));

        $payment = TinkoffPay::findOne(['id' => $body['OrderId']]);
        $payment->status = $body['Status'];
        $payment->response = $body;
        $payment->save();

        if($payment->status == "CONFIRMED") {
            self::CreateMonthUser($payment->courseId, $payment->monthId, $payment->userId, $payment->type, $payment->id);
        }

        return "OK";
    }

    public static function CreateMonthUser($courseId, $monthId, $userId, $type, $paymentId = null, $noStream = false)
    {
        $user = Users::find()
            ->where(['id' => $userId])
            ->with('months')
            ->with(['streams' => function($query) use ($courseId){
                return $query->where(['courseId' => $courseId]);
            }])
            ->one();

        $userMonths = [];
        if(!empty($user->months) > 0)
            foreach ($user->months as $userMonth)
                array_push($userMonths, $userMonth->id);

        $new = empty($user->streams);

        if($new)
        {
            if(!$noStream)
            {
                //Add to stream
                $stream = new UsersStream();
                $stream->userId = $userId;
                $stream->courseId = $courseId;
                $stream->monthId = $monthId;
                $stream->type = $type;
                $stream->save();
            }

            //Register this month
            $bcMonth = new BoughtCourses();
            $bcMonth->userId = $userId;
            $bcMonth->courseId = $courseId;
            $bcMonth->monthId = $monthId;
            $bcMonth->streamId = $stream->id;
            $bcMonth->paymentId = $paymentId;
            $bcMonth->save();
            array_push($userMonths, $monthId);

            if($type != AppController::STREAM_TYPE_DEMO)
            {
                //Give future months if subscription
                switch ($type)
                {
                    case AppController::GIFT_TYPE_SHORT:
                        $remains = 2;
                        break;
                    case AppController::GIFT_TYPE_LONG:
                        $remains = 11;
                        break;
                    default:
                        $remains = 0;
                }

                if($remains > 0)
                {
                    $months = Months::find()
                        ->where(['courseId' => $courseId])
                        ->orderBy(['dateFrom' => SORT_ASC])
                        ->all();

                    $flag = false;
                    foreach ($months as $month)
                    {
                        if($month->id == $monthId)
                            $flag = true;

                        if($flag && $remains > 0)
                        {
                            if(ArrayHelper::isIn($month->id, $userMonths))
                                continue;

                            $boughtCourse = new BoughtCourses();
                            $boughtCourse->userId = $userId;
                            $boughtCourse->monthId = $month->id;
                            $boughtCourse->courseId = $courseId;
                            $boughtCourse->streamId = $stream->id;
                            $boughtCourse->giftedByMonthId = null;
                            $boughtCourse->giftedByBC = $bcMonth->id;
                            $boughtCourse->paymentId = $paymentId;
                            $boughtCourse->save();

                            array_push($userMonths, $month->id);
                            $remains--;
                        }
                    }

                    //Fix remains amount
                    $stream->remains = $remains;
                    $stream->save();
                }

                //Register gifts
                self::RegisterGifts($monthId, $userId, $type, $paymentId, $bcMonth, $userMonths);
            }
        }
        else
        {
            //Just give current month
            if(!ArrayHelper::isIn($monthId, $userMonths))
            {
                $bcMonth = new BoughtCourses();
                $bcMonth->userId = $userId;
                $bcMonth->courseId = $courseId;
                $bcMonth->monthId = $monthId;
                $bcMonth->paymentId = $paymentId;
                $bcMonth->save();

                //Register gifts
                self::RegisterGifts($monthId, $userId, $type, $paymentId, $bcMonth, $userMonths);
            }
        }

        return true;
    }

    static function RegisterGifts($monthId, $userId, $type, $paymentId, $bcMonth, $userMonths)
    {
        $gifts = GiftMonths::getGiftsByType($monthId, $type);

        foreach ($gifts as $gift)
        {
            if(ArrayHelper::isIn($gift->gift->id, $userMonths))
                continue;

            $boughtCourse = new BoughtCourses();
            $boughtCourse->userId = $userId;
            $boughtCourse->monthId = $gift->gift->id;
            $boughtCourse->courseId = $gift->gift->courseId;
            $boughtCourse->streamId = null;
            $boughtCourse->giftedByMonthId = $monthId;
            $boughtCourse->giftedByBC = $bcMonth->id;
            $boughtCourse->paymentId = $paymentId;
            $boughtCourse->save();

            array_push($userMonths, $gift->month->id);
        }
    }
}
