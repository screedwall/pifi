<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;

?>
<div class="container">
    <div class="site-error text-center">

        <h1><?= Html::encode($this->title) ?></h1>

        <img src="/img/404.png" alt="" class="error404">

        <h3>
            УПС!
        </h3>

        <?php if(Yii::$app->response->statusCode != 404): ?>
        <h4>
            <p>Кажется у нас тут проблемка, но не переживайте мы всё пофиксим!😉</p>
            <p>Вы можете ускорить процесс скинув скрин сообщение ниже 👇 администратору</p>
        </h4>
        <?php endif; ?>

        <div class="alert alert-danger">
            <?= nl2br(Html::encode($message)) ?>
        </div>
    </div>
</div>
