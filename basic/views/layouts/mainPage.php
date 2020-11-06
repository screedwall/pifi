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
//            ['label' => 'Отзывы', 'url' => ['/reviews']],
//            ['label' => 'Контакты', 'url' => ['/contacts']],
            ['label' => 'О нас', 'url' => ['/', '#' => 'section-about']],
//            [
//                'label' => "<img src='/img/instagram-icon.svg' alt='instagram'>",
//                'options'=> ['class'=>'socials_item'],
//                'linkOptions'=>['class'=>'socials_link'],
//                'url' => 'https://www.instagram.com/pifi_school',
//            ],
//            [
//                'label' => "<img src='/img/youtube-icon.svg' alt='youtube'>",
//                'options'=> ['class'=>'socials_item'],
//                'linkOptions'=>['class'=>'socials_link'],
//                'url' => 'https://www.youtube.com/channel/UCY1a1U9BAQ2lTg_DnuhLb7A',
//            ],
//            [
//                'label' => "<img src='/img/vk-icon.svg' alt='vk'>",
//                'options'=> ['class'=>'socials_item'],
//                'linkOptions'=>['class'=>'socials_link'],
//                'url' => 'https://vk.com/pifi_school',
//            ],
//            [
//                'label' => '8 (927) 488-05-26',
//                'url' => 'tel:89274880526',
//                'linkOptions'=>['class'=>'phone']
//            ],


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
                ['label' => 'Профиль', 'url' => ['/auth/login']]
            )),
            Yii::$app->user->isGuest ? (
                ['label' => 'Войти', 'url' => ['/auth/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/auth/logout'], 'post')
                . Html::submitButton(
                    'Выйти (' . Yii::$app->user->identity->name . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();
    ?>
    
    <?= $content ?>
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
