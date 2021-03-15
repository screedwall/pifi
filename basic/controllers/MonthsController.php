<?php

namespace app\controllers;

use app\models\BoughtCourses;
use app\models\Months;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use Yii;

class MonthsController extends \yii\web\Controller
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

    public function actionView($id)
    {
        $demo = false;

        $user = Yii::$app->user->identity;
        $error = true;
        if(!$user->isAdmin() && !$user->isTeacher())
        {
            $boughtCourse = BoughtCourses::find()
                ->where(['monthId' => $id, 'userId' => Yii::$app->user->identity->getId()])
                ->with('stream')
                ->one();

            if (!empty($boughtCourse)) {
                foreach (Yii::$app->user->identity->months as $month)
                    if ($month->id == $id)
                        $error = false;

                if (!empty($boughtCourse->stream)) {
                    if ($boughtCourse->isDemo && !$boughtCourse->isDemoContinued)
                        $demo = true;
                }
            }
        }
        else
            $error = false;


        if(!$error)
        {
            $model = Months::find()
                ->where(['id' => $id])
                ->with(['lessons' => function($q) use ($demo){
                    if($demo)
                        $q = $q->limit(2);

                    return $q->orderBy(['lessonDate' => SORT_ASC]);
                }])->one();

            return $this->render('view', [
                'model' => $model,
                'id' => $id,
                'demo' => $demo,
            ]);
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    protected function findMonth($id)
    {
        if (($model = Months::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}
