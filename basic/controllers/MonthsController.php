<?php

namespace app\controllers;

use app\models\Months;
use yii\web\NotFoundHttpException;
use Yii;

class MonthsController extends \yii\web\Controller
{
    public function actionView($id)
    {
        $error = true;
        if(count(Yii::$app->user->identity->months) > 0)
            foreach (Yii::$app->user->identity->months as $month)
                if($month->id == $id)
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
