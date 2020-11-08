<?php

namespace app\controllers;

use app\models\BoughtCourses;
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

        $months = [];
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

    public function actionBuy()
    {
        $courseId = \Yii::$app->request->post('course');
        $monthsIds = \Yii::$app->request->post('months');

        $months = mb_split(',', $monthsIds);

        $userId = \Yii::$app->user->identity->getId();

        if(!empty($monthsIds))
        {
            $current = BoughtCourses::find()->where(['userId' => $userId])->all();
            foreach ($current as $item) {
                $item->delete();
            }
            foreach ($months as $monthId) {
                $boughtCourse = new BoughtCourses();
                $boughtCourse->userId = $userId;
                $boughtCourse->monthId = $monthId;
                $boughtCourse->courseId = $courseId;
                $boughtCourse->save(false);
            }
        }
        elseif(!empty($courseId))
        {
            $course = Courses::findOne(['id' => $courseId]);
            $months = $course->months;

            $current = BoughtCourses::find()->where(['userId' => $userId])->all();
            foreach ($current as $item) {
                $item->delete();
            }
            if(isset($months))
                foreach ($months as $month) {
                    $boughtCourse = new BoughtCourses();
                    $boughtCourse->userId = $userId;
                    $boughtCourse->monthId = $month->id;
                    $boughtCourse->courseId = $course->id;
                    $boughtCourse->save();
                }
        }

        return $this->redirect(['/profile']);
    }

}
