<?php

namespace app\controllers;

use app\models\Courses;
use app\models\Teachers;
use yii\web\NotFoundHttpException;

class TeachersController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = Teachers::find()->orderBy(['id' => SORT_ASC])->all();
        return $this->render('index', [
            'model' => $model
        ]);
    }

    public function actionView($id)
    {
        $this->layout = 'mainPage';
        $model = $this->findTeacher($id);
        return $this->render('view', [
            'model' => $model
        ]);
    }

    protected function findTeacher($id)
    {
        if (($model = Teachers::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}
