<?php

namespace app\controllers;

use app\models\CoursesSearch;
use yii\web\Controller;
use Yii;

class AdminController extends Controller
{

    public function actionIndex()
    {
        $searchModel = new CoursesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

}