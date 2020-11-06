<?php


namespace app\controllers;

use app\models\LoginForm;
use app\models\Users;
use \yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;
use yii\httpclient\Client;

class AuthController extends Controller
{
    public function actions()
    {
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    public function onAuthSuccess($client)
    {
        return 1;
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $code = Yii::$app->request->get('code');
        if (isset($code)) {
            $accessToken = Yii::$app->authClientCollection->getClient('vkontakte')->fetchAccessToken($code);
            if(isset($accessToken)){
                $client = new Client(['baseUrl' => 'https://api.vk.com/method']);
                $response = $client->get('users.get', ['access_token' => $accessToken->getToken(), 'v' => '5.95'])->send();
                if ($response->isOk) {
                    $token = $response->data['response'][0];
                    if(isset($token)){
                        $identity = Users::findIdentityByAccessToken($token);
                        if($identity != null){
                            Yii::$app->user->login($identity, 3600*24*30);

                            return $this->goHome();
                        }else{
                            $user = new Users();
                            $user->login = 'vk'.$token['id'];
                            $user->name = $token['first_name']." ".$token['last_name'];
                            $user->vk = $token['id'];
                            $user->role = 2;
                            $user->password = Yii::$app->getSecurity()->generateRandomString();
                            $user->save(false);

                            Yii::$app->user->login($user, 3600*24*30);
                            return $this->goHome();
                        }
                    }
                }
            }
        }

        $model->password = '';
        return $this->render('index', [
            'model' => $model,
            'token' => $token
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}