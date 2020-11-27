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
        $handle = fopen(Yii::getAlias('@webroot')."/users_export.csv", "r");
        while (($fileop = fgetcsv($handle, 1000, ",")) !== false)
        {
            $model = new Users();
            $model->id = $fileop[0];
            $model->login = $fileop[1];
            $model->name = $fileop[2];
            $model->email = $fileop[1];
            $model->vk = $fileop[4];
            $model->description = $fileop[5];
            $model->role = $fileop[6];
            $model->password = $fileop[9];
            $model->createdAt = $fileop[11];
            $model->save(false);
        }

        return "OK";
    }
}
