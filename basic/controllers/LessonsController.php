<?php

namespace app\controllers;

use app\models\LessonAttachments;
use Yii;
use app\models\Lessons;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

class LessonsController extends \yii\web\Controller
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
        $model = $this->findModel($id);

        $user = Yii::$app->user->identity;
        $error = true;
        if(!$user->isAdmin() && !$user->isTeacher())
        {
            if(count(Yii::$app->user->identity->months) > 0)
                foreach (Yii::$app->user->identity->months as $month)
                    if($month->id == $model->monthId)
                        $error = false;
        }
        else
            $error = false;

        if(!$error) {
            return $this->render('view', [
                'model' => $model
            ]);
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionDownload($id)
    {
        $attach = LessonAttachments::findOne(['id' => $id]);

        if($attach !== null){
            return Yii::$app->response->sendFile(Yii::getAlias('@webroot')."/".$attach->path, $attach->name);
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
