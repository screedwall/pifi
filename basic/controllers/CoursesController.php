<?php

namespace app\controllers;

use app\models\Courses;
use app\models\Lessons;
use app\models\Mounths;
use yii\web\NotFoundHttpException;

class LessonsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
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
        if (($model = Mounths::findOne($id)) !== null) {
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
