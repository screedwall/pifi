<?php

namespace app\controllers;

use app\models\Courses;
use app\models\Months;
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

        $getMonths = $request->get('months');
        $getCourse = $request->get('course');

        if (!empty($getMonths))
        {
            $queryMonths = \app\models\Months::find()->select(['courseId', 'id'])->orderBy(['id' => SORT_ASC])->where(['in', 'id', $getMonths])->asArray()->all();

            $prev = $queryMonths[0]['courseId'];
            $error = false;
            foreach ($queryMonths as $month){
                if($prev <> $month['courseId'])
                    $error = true;
            }

            if(!$error)
            {
                $months = [];
                foreach ($queryMonths as $month){
                    array_push($months, Months::findOne(['id' => $month['id']]));
                }
                $course = \app\models\Courses::findOne(['id' => $prev]);
                $error = count($course->months) == 1;
            }

        }
        elseif(!empty($getCourse))
        {
            $course = \app\models\Courses::findOne(['id' => $getCourse]);
            $error = $course == null;
        }
        else
            $error = true;

        if(!$error)
            return $this->render('index', [
                'course' => $course,
                'months' => $months,
            ]);
        else
            throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }

}
