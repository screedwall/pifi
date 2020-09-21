<?php


namespace app\controllers;

use app\models\Users;
use \yii\web\Controller;
use yii\web\NotFoundHttpException;

class LoginController extends Controller
{


    protected function findModel($id)
    {
        if (($model = Users::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}