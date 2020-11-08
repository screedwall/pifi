<?php

namespace app\controllers;

use app\models\Users;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['profile', 'change-password'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'change-password' => ['post'],
                ],
            ],
        ];
    }

    public function actionProfile()
    {
        $model = Users::findOne(Yii::$app->user->identity->getId());

        $courses = Yii::$app->user->identity->courses;
        $months = Yii::$app->user->identity->months;

        if ($model->load(Yii::$app->request->post())&&$model->save(false)) {
            return $this->redirect(['profile']);
        }

        return $this->render('profile', [
            'model' => $model,
            'courses' => $courses,
            'months' => $months,
        ]);
    }

    public function actionChangePassword()
    {
        $model = Users::findOne(['id' => Yii::$app->user->identity->getId()]);
        $password = Yii::$app->request->post('password');
        if(!empty($password))
        {
            $model->password = Yii::$app->getSecurity()->generatePasswordHash($password);
            if ($model->save(false)){
                return $this->redirect(['/auth/logout']);
            }
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));

    }
}
