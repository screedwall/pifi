<?php
use yii\authclient\OAuth2;
use evgeniyrru\yii2slick\Slick;
use yii\helpers\Html;
use yii\web\JsExpression;
use newerton\fancybox;
/* @var $this yii\web\View */

$this->title = Yii::$app->name;
?>
<main>
    <section class="section-main">
    <div class="container">
        <div class="row">
            <div class="col-md-7 col-lg-6">

                <h1 class="section-main__pretitle">Онлайн-школа "Pi-Fi"</h1>

                <p class="title-1 section-main__title">
                    Увеличим твой результат <span class="word">ЕГЭ</span>
                    на <span class="word">5-20</span> баллов или подарим
                    курс <span class="word">БЕСПЛАТНО!</span>
                </p>

                <p class="text section-main__text">
                    Фишка нашего обучения в том, что акцент направлен на действительно значимые детали, а не на заучивание правил
                    и теорем. Все полученные знания закрепляем практикой!
                </p>



                <div class="row align-items-center">
                    <div class="col-md-5 col-xs-8">
                        <a href="/courses" type="button" class="button" data-fancybox data-src="#popup-form"><span class="button__name">Хочу на курсы!</span></a>
                    </div>
                    <div class="col-md-5 col-xs-4 d-flex justify-content-center">
                        <a href="https://www.youtube.com/watch?v=75VXL_x3ZvU&feature=youtu.be" target="_blank" class="play" data-fancybox>
                            <div class="play__left">
                                <span class="play__icon"></span>
                            </div>

                            <div class="play__right">
                                <p class="play__title">
                                    <span class="play__name">Промо-ролик</span> <br>
                                    о нашем курсе
                                </p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
    <section id="section-about" class="section section-about">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="visible-lg col-md-6">
                    <img src="img/photo.png" alt="about" class="section-about__photo">
                </div>

                <div class="col-md-6">
                    <h2 class="title-1 section-about__title">Кто такие <span class="word">Pi-Fi</span>?</h2>

                    <div class="section-about__text-block">
                        <p class="text section-about__text"><span class="word">Pi-Fi</span> - это онлайн-школа с огромной командой специалистов! Мы профессионально подготовим тебя к ЕГЭ по любому предмету. Каждый из нас станет для тебя не только преподавателем, но и хорошим другом!</p>

                        <p class="text section-about__text">В основе подготовки стоит <span class="word">Мастер группа</span>, в ней у каждого есть свой личный наставник, который помогает с любыми сложностями, возникающими в ходе решения практики. После каждого урока у тебя остаётся запись.</p>

                        <p class="text">Все ещё сомневаешься? Тогда лови <span class="word">Бесплатный урок</span>, в котором наглядно показан принцип нашего обучения!</p>
                    </div>

                    <img src="img/photo.png" alt="about" class="section-about__photo visible-xs">

                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <a href="https://youtu.be/aLY927K0KPI" class="button button--fluid button--play" data-fancybox=""><span class="button__name">Бесплатный урок</span><span class="button__play"></span></a>
                        </div>

                        <div class="col-md-6 hidden-xs">
                            <a href="#section-master-group" class="button-outline button-outline--fluid" data-pagescroll="#section-master-group">Узнать подробнее</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="section-master-group" class="section section-master-group">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="title-1 section-master-group__title">Что такое Мастер группа?</h2>
                </div>
            </div>

            <div class="row text-center">
                <div class="col-md-12">
                    <p class="subtitle section-master-group__description half">
                        Мастер группа - это платный <span class="word">ГОДОВОЙ</span> курс онлайн-подготовки к ЕГЭ, преимуществами
                        которой являются <span class="word">цена</span> и <span class="word">удобство</span> обучения!
                    </p>
                </div>
            </div>

            <div class="row section-master-group__items">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="about-card about-card--border about-card--border--orange">
                                <h3 class="title-3 about-card__title">Минимум времени - максимум пользы</h3>
                                <p class="text">В Мастер группе 3 занятия в неделю по 1,5 часа (12 занятий в месяц). Это позволяет изучить тему полностью и углубленно!</p>
                            </div>

                            <div class="about-card about-card--border about-card--border--yellow">
                                <h3 class="title-3 about-card__title">Путь к успеху лежит через практику</h3>
                                <p class="text">После занятий ты получаешь практическую домашнюю работу для закрепления материала, а если вдруг ты не сможешь ее сделать, то к тебе на помощь придет твой наставник (куратор)</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="about-card about-card--border about-card--border--blue">
                                <h3 class="title-3 about-card__title">Секреты - не для всех</h3>
                                <p class="text">Занятия закрытые и проходят на специальной платформе для вебинаров, Вход осуществляется через личный кабинет сайта, а после каждого занятия тебя ждет красиво оформленная теория 😊</p>
                            </div>

                            <div class="about-card about-card--border about-card--border--orange">
                                <h3 class="title-3 about-card__title">Не уверен? Проверь!</h3>
                                <p class="text">Demo Мастер группа - это недельная версия Мастер группы. Ты можешь обучаться с нами ПРОБНУЮ неделю всего за 200р! 😱</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 hidden-xs">
                    <img src="img/master-group.svg" alt="master-group">
                </div>
            </div>

            <div class="row text-center">
                <div class="col-md-12">
                    <p class="text section-master-group__text half">
                        Хочешь стать частью нашей образовательной системы и сдать ЕГЭ на 90-100 баллов? Записывайся в группу!
                    </p>
                </div>
            </div>

            <div class="row text-center">
                <div class="col-md-12 mb-4 mb-md-0">
                    <a href="/courses" class="button button--fluid" data-fancybox="" data-src="#popup-form"><span class="button__name">Хочу в Мастер группу!</span></a>
                </div>
            </div>
        </div>
    </section>
    <section class="section section-curator">
        <div class="container text-center">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="title-1 section-curator__title">Кто такой наставник (куратор)?</h2>

                    <img src="img/curator.svg" alt="curator" class="section-curator__img">
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <p class="section-curator__text">
                        <span class="word">Наставник</span> - твой персональный помощник и друг, который уже сдал ЕГЭ на 90+ баллов. Он проверит твою домашнюю работу, даст совет, всегда готов
                        помочь и ответить на твои вопросы! 👍
                    </p>

                    <a href="/teachers" class="button button--long ml-auto mr-auto" data-pagescroll="#section-teachers"><span class="button__name">Посмотреть преподавателей</span></a>
                </div>
            </div>
        </div>
    </section>
    <section id="section-advantages" class="section section-advantages">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2 class="title-1 section-advantages__title">Почему нас выбирают?</h2>

                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-lg-4">
                    <div class="advantage">
                        <img src="img/advantage-icon-01.svg" alt="icon" class="advantage__icon">
                        <h3 class="title-3 advantage__title">100% гарантия результата</h3>
                        <p class="text">Мы - единственная образовательная платформа, которая дает гарантию того, что за 3 месяца вы увеличите свой результат ЕГЭ от 5 до 20 баллов за 3 месяца или мы подарим курс БЕСПЛАТНО!</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="advantage">
                        <img src="img/advantage-icon-02.svg" alt="icon" class="advantage__icon">
                        <h3 class="title-3 advantage__title">Цены ниже чем у репетиторов!</h3>
                        <p class="text">Целый курс обойдётся тебе в стоимость, которую ты тратишь на несколько занятий у репетитора! Но разобрать мы успеваем в разы больше тем. Всего 139 руб/ЧАС занятия!</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="advantage">
                        <img src="img/advantage-icon-03.svg" alt="icon" class="advantage__icon">
                        <h3 class="title-3 advantage__title">Система мотивации учеников</h3>
                        <p class="text">Чек-листы по мотивации, целеполаганию и тайм менеджменту для школьников</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-4">
                    <div class="advantage">
                        <img src="img/advantage-icon-04.svg" alt="icon" class="advantage__icon">
                        <h3 class="title-3 advantage__title">Современный формат вебинаров</h3>
                        <p class="text">Вебинары рассчитаны на минимальное количество времени, за которое мы успеваем разобрать самое важное и основное, не отвлекаясь на второстепенные вещи</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="advantage">
                        <img src="img/advantage-icon-05.svg" alt="icon" class="advantage__icon">
                        <h3 class="title-3 advantage__title">Персональный наставник</h3>
                        <p class="text">Наставник не только поможет тебе с любым спорным вопросом или непонятной задачей, но и зарядит мощнейшей мотивацией на учебу, а также разбавит твои трудовые будни интересными викторинами.</p>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="advantage">
                        <img src="img/advantage-icon-06.svg" alt="icon" class="advantage__icon">
                        <h3 class="title-3 advantage__title">Интересно, познавательно и выгодно!</h3>
                        <p class="text">Молодые, современные преподаватели, поэтому НЕскучная и очень интересная подача материала!</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="section-price" class="section section-price">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="title-1 section-price__title">Услуги и цены</h2>
                    <p class="subtitle section-price__subtitle">
                        Мы предлагаем Вам поработать по принципу <span class="word">«Мастер Группа»</span> или поучаствовать в пробном, недельном <br>
                        курсе <span class="word">«DEMO Мастер Группа»</span> за символическую плату - <span class="word">200р!</span> После этого у Вас будет возможность доплатить и стать участником <br>
                        полноценной Мастер группы!
                    </p>
                </div>
            </div>

            <div class="row section-price__products">
                <div class="col-md-12">
                    <div class="product-card">
                        <h3 class="product-card__title">Мастер группа</h3>

                        <ul class="product-characteristics product-card__characteristics">
                            <li class="product-characteristics__item">1 месяц подготовки</li>
                            <li class="product-characteristics__item">12 онлайн-занятий + записи</li>
                            <li class="product-characteristics__item">11 домашних заданий</li>
                            <li class="product-characteristics__item">Ваш личный наставник</li>
                            <li class="product-characteristics__item">Система мотиваций</li>
                        </ul>

                        <p class="product-card__price">2500 руб.</p>

                        <a href="/courses" class="button button--long product-card__button" data-fancybox="" data-src="#popup-form"><span class="button__name">Хочу в Мастер группу!</span></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
<!--    <section id="section-reviews" class="section section-reviews">-->
<!--        --><?//=Slick::widget([
//            // HTML tag for container. Div is default.
//            'itemContainer' => 'div',
//
//            // HTML attributes for widget container
//            'containerOptions' => ['class' => 'slider-reviews section-reviews__slider'],
//
//            'jsPosition' => yii\web\View::POS_READY,
//
//            'events' => [
//                'edge' => 'function(event, slick, direction) {
//                           console.log(direction);
//                           // left
//                      }'
//            ],
//
//            // Items for carousel. Empty array not allowed, exception will be throw, if empty
//            'items' => [
//                Html::img('/img/preview-01.jpg'),
//                Html::img('/img/preview-02.png'),
//                Html::img('/img/preview-03.png'),
//                Html::img('/img/preview-04.png'),
//            ],
//
//            // HTML attribute for every carousel item
//            'itemOptions' => ['class' => 'cat-image'],
//
//            // settings for js plugin
//            // @see http://kenwheeler.github.io/slick/#settings
//            'clientOptions' => [
//                'autoplay' => false,
//                'dots'     => true,
//                'slidesToShow' => 3,
//                'slidesToScroll' => 1,
//                'infinite' => false,
//                'arrows' => true,
//            ],
//        ]);?>
<!--    </section>-->
    <section class="section section-questions">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <h2 class="title-1 section-questions__title">Частые вопросы</h2>

                    <div class="accordion section-questions__accordion two-thirds">
                        <div class="accordion__item" data-toggle="collapse" data-parent="#accordion" href="#collapse1">
                            <div class="accordion__heading">
                                <h3 class="accordion__title"><span class="accordion__num">01</span>Откуда мне знать, что вы не обманете меня?</h3>
                            </div>

                            <div id="collapse1" class="accordion__panel panel-collapse collapse" style="">
                                <div class="accordion__content">
                                    <p class="text accordion__text">
                                        Преподаванием занимаемся уже 5 лет и мне нет смысла портить свою репутацию из-за краткосрочной выгоды. Это бессмысленно как с моральной, так и с экономической точки зрения, так как многие ученики приходят по рекомендациям других учеников.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="accordion__item" data-toggle="collapse" data-parent="#accordion" href="#collapse2">
                            <div class="accordion__heading">
                                <h3 class="accordion__title"><span class="accordion__num">02</span>Если я не смогу присутствовать на занятии, будет запись занятия?</h3>
                            </div>

                            <div id="collapse2" class="accordion__panel panel-collapse collapse" style="">
                                <div class="accordion__content">
                                    <p class="text accordion__text">
                                        Да, конечно будет! Запись будет доступна в личном кабинете на этом сайте.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="accordion__item" data-toggle="collapse" data-parent="#accordion" href="#collapse3">
                            <div class="accordion__heading">
                                    <h3 class="accordion__title"><span class="accordion__num">03</span>Можно оплачивает помесячно, а не сразу за год?</h3>
                            </div>

                            <div id="collapse3" class="accordion__panel panel-collapse collapse" style="">
                                <div class="accordion__content">
                                    <p class="text accordion__text">
                                        Нужно! Оплата происходит каждый месяц, а не сразу за год. В любой момент, при желании, вы сможете закончить обучение.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="accordion__item" data-toggle="collapse" data-parent="#accordion" href="#collapse4">
                            <div class="accordion__heading">
                                <h3 class="accordion__title"><span class="accordion__num">04</span>Вдруг я ничего не пойму?</h3>
                            </div>

                            <div id="collapse4" class="accordion__panel panel-collapse collapse" style="">
                                <div class="accordion__content">
                                    <p class="text accordion__text">
                                        Это маловероятно. Ведь уже порядка 2500 учеников обучились в Мастер группе и всё прекрасно поняли, вы вряд ли будете исключением из правила.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <p class="text section-questions__text">
                        Запишись к нам на курсы и подготовься к ЕГЭ без нервов и стресса!
                    </p>

                    <a href="/courses" class="button ml-auto mr-auto" data-fancybox="" data-src="#popup-form"><span class="button__name">Записаться на курсы</span></a>
                </div>
            </div>
        </div>
    </section>
</main>