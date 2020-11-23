<?php

namespace app\controllers;

use app\models\BoughtCourses;
use app\models\Courses;
use app\models\Months;
use app\models\UsersStream;
use yii\filters\AccessControl;
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

        if(!empty($getMonth))
            $month = Months::findOne(['id' => $getMonth]);

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

        $course = Courses::findOne(['id' => $courseId]);
        $getMonth = Months::findOne(['id' => $monthId]);

        $type = \Yii::$app->request->post('type');
        $userId = \Yii::$app->user->identity->getId();
        $currentMonth = $course->currentMonth();
        $error = false;
        $months = [];

        if($type == 'course')
        {
            array_push($months, $course->currentMonth());
        }
        elseif ($type == 'short')
        {
            $_months = $course->getMonths()->orderBy('dateFrom')->all();
        }
        elseif ($type == 'long')
        {
            $months = $course->months;
        }
        elseif ($type == 'month')
        {
            array_push($months, $getMonth);
        }

        foreach ($months as $month)
        {
            $boughtCourse = new BoughtCourses();
            $boughtCourse->userId = $userId;
            $boughtCourse->monthId = $month->id;
            $boughtCourse->courseId = $course->id;
            $boughtCourse->save();

            foreach ($month->gifts as $gift)
            {
                $boughtCourse = new BoughtCourses();
                $boughtCourse->userId = $userId;
                $boughtCourse->monthId = $gift->id;
                $boughtCourse->courseId = $course->id;
                $boughtCourse->save();
            }
        }

        if($type != 'month')
        {
            $stream = new UsersStream();
            $stream->userId = $userId;
            $stream->courseId = $course->id;
            $stream->monthId = $currentMonth->id;
            $stream->type = $type;
            $stream->boughtId = $boughtCourse->id;
            $stream->save();
        }

        return $this->redirect(['/profile']);
    }

}
