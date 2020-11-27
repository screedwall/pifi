<?php

namespace app\controllers;

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
        $error = true;
        if(!Yii::$app->user->identity->isAdmin())
        {
            if(count(Yii::$app->user->identity->months) > 0)
                foreach (Yii::$app->user->identity->months as $month)
                    if($month->id == $id)
                        $error = false;
        }
        else
            $error = false;


        if(!$error)
        {
            $model = $this->findMounth($id);
            return $this->render('view', [
                'model' => $model,
                'id' => $id
            ]);
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

}
