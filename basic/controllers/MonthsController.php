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
        $error = false;
        $demo = false;

        $boughtCourse = BoughtCourses::find()
            ->where(['monthId' => $id, 'userId' => Yii::$app->user->identity->getId()])
            ->with('stream')
            ->one();

        if(!empty($boughtCourse))
        {
            $error = false;
            if(!empty($boughtCourse->stream))
            {
                if($boughtCourse->stream->type == AppController::STREAM_TYPE_DEMO)
                    $demo = true;
            }
        }


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
