<?php

namespace app\controllers;

use app\models\Courses;
use app\models\Lessons;
use app\models\Months;
use phpDocumentor\Reflection\Types\Parent_;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Yii;

class CoursesController extends Controller
{
    public function actionIndex()
    {
        $model = Courses::find();

        $request = Yii::$app->request;
        $subject = $request->get('subject');
        $exam = $request->get('exam');

        if(!empty($subject))
        {
            $model = $model->where(['subject' => $subject]);
        }

        if(!empty($exam)&&!empty($subject))
        {
            $model = $model
                ->where(['subject' => $subject])
                ->andWhere(['examType' => $exam]);
        }

        return $this->render('index', [
            'model' => $model
                        ->orderBy(['id' => SORT_DESC])
                        ->with('teacher')
                        ->with(['months' => function($query) {
                        return $query
                            ->with('lessons')
                            ->orderBy(['dateFrom' => SORT_ASC]);
                    }])->all(),
            'subjectRequest' => $subject,
            'examRequest' => $exam,
        ]);
    }

    public function actionView($id)
    {
        $this->layout = 'mainPage';
        $model = $this->findCourse($id);
        return $this->render('view', [
            'model' => $model
        ]);
    }
    public function actionLesson($id)
    {
        $model = $this->findLesson($id);
        return $this->render('view', [
            'model' => $model
        ]);
    }
    protected function findCourse($id)
    {
        if (($model = Courses::find()
                ->where(['id' => $id])
                ->with(['months' => function($query) {
                    return $query
                        ->with('lessons')
                        ->orderBy(['dateFrom' => SORT_ASC]);
                }])
                ->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    protected function findMounth($id)
    {
        if (($model = Months::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    protected function findLesson($id)
    {
        if (($model = Lessons::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
