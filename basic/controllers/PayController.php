<?php

namespace app\controllers;

use app\models\Courses;
use yii\web\Controller;

class PayController extends Controller
{
    public function actionIndex($id)
    {
        $model = Courses::findOne($id);
        return $this->render('index', [
            'model' => $model
        ]);
    }

}
