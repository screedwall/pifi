<?php

namespace app\controllers;

use Yii;
use app\models\Lessons;
use yii\web\NotFoundHttpException;

class LessonsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $error = true;
        if(count(Yii::$app->user->identity->months) > 0)
            foreach (Yii::$app->user->identity->months as $month)
                if($month->id == $model->monthId)
                    $error = false;

        if(!$error) {
            return $this->render('view', [
                'model' => $model
            ]);
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    protected function findModel($id)
    {
        if (($model = Lessons::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
