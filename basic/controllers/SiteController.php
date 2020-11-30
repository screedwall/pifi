<?php

namespace app\controllers;

use app\models\Users;
use Yii;
use yii\console\ExitCode;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = '/mainPage';
        return $this->render('index');
    }

    public function actionSubjects()
    {
        return $this->render('subjects');
    }

    public function actionCommand($message = 'hello world')
    {
        $users = Users::find()->all();
        $handle = fopen(Yii::getAlias('@webroot')."/users_export.csv", "r");
        while (($fileop = fgetcsv($handle, 1000, ",")) !== false)
        {
            $model = new Users();
            if(Users::find()
                    ->where(['id' => $fileop[0]])
                    ->orWhere(['vk' => $fileop[32]])
                    ->count() > 0)
            {
                continue;
            }
            $model->id = $fileop[0];
            $model->login = $fileop[4];
            $model->name = $fileop[1]." ".$fileop[2];
            $model->email = $fileop[4];
            $model->vk = $fileop[32];
            $model->description = $fileop[9];
            $model->role = 2;
            $model->password = $fileop[5];
            $model->createdAt = $fileop[18];
            $model->save(false);
        }

        return "OK";
    }
}
