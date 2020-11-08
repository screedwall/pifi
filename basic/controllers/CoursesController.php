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
        return $this->render('index');
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
        if (($model = Courses::findOne($id)) !== null) {
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
