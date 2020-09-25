<?php
use yii\authclient\OAuth2;
/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Congratulations!!</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
    </div>

    <div class="body-content">

        <pre><?= "" ?></pre>

    </div>
</div>

<?php
$client = Yii::$app->authClientCollection->getClient('vkontakte');
$client_id = Yii::$app->request->get('authclient');
$url = $client->buildAuthUrl();

echo $url;
$code = Yii::$app->getRequest()->get('code');
if(!isset($code))
    Yii::$app->getResponse()->redirect($url);
else
    $accessToken = $client->fetchAccessToken($code);
//echo $accessToken;
//if(!isset($code))
//    Yii::$app->getResponse()->redirect($url);
//else
//    $accessToken = $client->fetchAccessToken($code);