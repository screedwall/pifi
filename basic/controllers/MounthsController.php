<?php

namespace app\controllers;

use app\models\Mounths;
use yii\web\NotFoundHttpException;

class MounthsController extends \yii\web\Controller
{
    public function actionView($id)
    {
        $model = $this->findMounth($id);
        return $this->render('view', [
            'model' => $model,
            'id' => $id
        ]);
    }
    protected function findMounth($id)
    {
        if (($model = Mounths::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

}
