<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .row.display-flex {
            display: flex;
            flex-wrap: wrap;
            padding: 10px;
        }
        .thumbnail {
            height: 100%;
        }
    </style>
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Html::img('/img/logo.svg', ['alt'=>Yii::$app->name]),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'header navbar-default navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'encodeLabels' => false,
        'items' => [
            ['label' => 'Главная', 'url' => ['/']],
            ['label' => 'Курсы', 'url' => ['/courses']],
            ['label' => 'Преподаватели', 'url' => ['/teachers']],
            ['label' => 'Предметы', 'url' => ['/subjects']],

            Yii::$app->user->isGuest ? '' : (
            Yii::$app->user->identity->isAdmin() ? (
            [
                'label' => 'Управление',
                'items' => [
                    '<li class="dropdown-header">Курсы, месяца, уроки</li>',
                    ['label' => 'Курсы', 'url' => ['/admin/courses']],
                    '<li class="dropdown-header">Пользователи</li>',
                    ['label' => 'Пользователи', 'url' => ['/admin/users']],
                    ['label' => 'Преподаватели', 'url' => ['/admin/teachers']],
                ],
            ]
            ) : (
                ['label' => 'О нас', 'url' => ['/', '#' => 'section-about']]
            )),
            Yii::$app->user->isGuest ? (
            ['label' => 'Войти', 'url' => ['/auth/login']]
            ) : (
            [
                'label' => Yii::$app->user->identity->name,
                'items' => [
                    ['label' => 'Личный кабинет', 'url' => ['/profile'], 'class' => ''],
                    '<hr>',
                    '<li>'
                    .Html::a('Выйти', \yii\helpers\Url::to(['/auth/logout']),
                        [
                            'data' => [
                                'method' => 'post',
                            ],
                            'class' => 'btn btn-link logout',
                        ]
                    )
                    .'</li>',
                ],
            ]
            )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p class="copy footer__copy"><span class="copy__name">Образовательная платформа «Pi-Fi».</span> <?= date('Y') ?> <span class="copy__symbol">&copy;</span> Все права защищены</p>
            </div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
