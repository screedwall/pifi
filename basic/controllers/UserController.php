<?php

namespace app\controllers;

use app\models\Users;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
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
        $model = Yii::$app->user->identity;

        $months = ArrayHelper::getColumn(Yii::$app->user->identity->getMonths()->select('id')->asArray()->all(), 'id');
        $courses = $model->getCourses()
            ->with('teacher')
            ->with(['months' => function($query){
                return $query->with('lessons');
            }])
            ->all();

        if ($model->load(Yii::$app->request->post())) {
            $model->save();
            return $this->render('profile', [
                'model' => Yii::$app->user->identity,
                'months' => $months,
                'courses' => $courses,
            ]);
        }

        return $this->render('profile', [
            'model' => Yii::$app->user->identity,
            'months' => $months,
            'courses' => $courses,
        ]);
    }

    public function actionChangePassword()
    {
        $model = Yii::$app->user->identity;
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
