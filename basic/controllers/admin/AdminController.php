<?php

namespace app\controllers\admin;

use yii\web\Controller;

class AdminController extends Controller
{

    public function actionIndex()
    {
        return $this->render('index');
    }

}