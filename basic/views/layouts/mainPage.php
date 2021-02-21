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
            'class' => 'header navbar-default navbar-fixed-top text-center',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'encodeLabels' => false,
        'items' => [
            ['label' => 'Главная', 'url' => ['/']],
            ['label' => 'Курсы', 'url' => ['/courses']],
            ['label' => 'Преподаватели', 'url' => ['/teachers']],
            ['label' => 'О нас', 'url' => ['/', '#' => 'section-about']],

            Yii::$app->user->isGuest ? '' : (
            Yii::$app->user->identity->isAdmin() ? (
            [
                'label' => 'Управление',
                'items' => [
                    ['label' => 'Курсы', 'url' => ['/admin/courses']],
                    ['label' => 'Купоны', 'url' => ['/admin/coupons']],
                    ['label' => 'Пользователи', 'url' => ['/admin/users']],
                    ['label' => 'Преподаватели', 'url' => ['/admin/teachers']],
                    ['label' => 'Статистика', 'url' => ['/admin/statistics']],
                    ['label' => 'Оплаты', 'url' => ['/admin/pay']],
                ],
            ]
            ) : (Yii::$app->user->identity->isTeacher() ?
                ['label' => 'Мои курсы', 'url' => ['/admin/courses']]
                : '')
            ),
            '<li class="socials__item hidden-xs hidden-sm"><a href="https://www.instagram.com/pifi_school/" target="_blank" class="socials__link"><img src="/img/instagram-icon.svg" alt="instagram" class="socials__icon"></a></li>'
            .'<li class="socials__item hidden-xs hidden-sm"><a href="https://www.youtube.com/channel/UCY1a1U9BAQ2lTg_DnuhLb7A" target="_blank" class="socials__link"><img src="/img/youtube-icon.svg" alt="youtube" class="socials__icon"></a></li>'
            .'<li class="socials__item hidden-xs hidden-sm"><a href="https://vk.com/pifi_school" target="_blank" class="socials__link"><img src="/img/vk-icon.svg" alt="vk" class="socials__icon"></a></li>',
            '<li>
                <a class="phone" href="tel:88006000362">8 (800) 600 0362
                <br>
                <small>Звонок бесплатный</small></a>
            </li>',
            Yii::$app->user->isGuest ? (
            ['label' => 'Войти', 'url' => ['/auth/login'], 'options' => ['class' => 'login-button']]
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
            ),
        ],
    ]);
    NavBar::end();
    ?>
    
    <?= $content ?>
</div>

<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4 text-center">
                    <a href="https://www.instagram.com/pifi_school/" target="_blank" class="socials__link"><img src="/img/instagram-icon.svg" alt="instagram" class="socials__icon"></a>
                    <a href="https://www.youtube.com/channel/UCY1a1U9BAQ2lTg_DnuhLb7A" target="_blank" class="socials__link"><img src="/img/youtube-icon.svg" alt="youtube" class="socials__icon"></a>
                    <a href="https://vk.com/pifi_school" target="_blank" class="socials__link"><img src="/img/vk-icon.svg" alt="vk" class="socials__icon"></a>
            </div>
            <div class="col-md-6 text-right">
                <?php \yii\bootstrap\Modal::begin([
                    'header' => '<h3>ДОГОВОР ПУБЛИЧНОЙ ОФЕРТЫ</h3>',
                    'toggleButton' => [
                        'label' => 'Договор оферты',
                        'tag' => 'a',
                        'class' => 'oferta',
                    ],
                    'options' => [
                        'class' => 'oferta-modal text-center'
                    ],
                ]) ?>

                <div>
                    <h3>ОБ ОКАЗАНИИ ПЛАТНЫХ ОБРАЗОВАТЕЛЬНЫХ УСЛУГ ПО ОБУЧЕНИЮ</h3>
                    <h4>г. Казань</h4>
                    <p>Настоящая публичная оферта (далее - <strong>«Оферта»</strong>) является предложением Индивидуального предпринимателя Ахтямова Алмаза Азатовича (ИНН 165125012795) (далее - <strong>ИП Ахтямов А.А.</strong>), заключить договор о предоставлении Доступа к образовательному сайту в сети интернет pi-fi.ru, как это определено ниже (далее - <strong>«Договор»</strong>) на условиях, содержащихся в Оферте, в соответствии со ст. 437 Гражданского кодекса Российской Федерации.</p>
                    <h4><strong>1. ЗАКЛЮЧЕНИЕ ДОГОВОРА. УСЛОВИЯ И ПОРЯДОК АКЦЕПТА</strong></h4>

                    <p>1.1. Настоящий Договор является официальным предложением (публичной Офертой) Индивидуального предпринимателя Ахтямова Алмаза Азатовича, действующего на Основании Свидетельства (<strong>ИНН</strong>:165125012795; <strong>ОГРНИП</strong>: 318169000221595; Юридический и фактический адрес г. Нижнекамск ул. Менделеева д.13а (в дальнейшем - Исполнитель) и содержит все существенные условия договора по оказанию платных образовательных услуг по обучению (в дальнейшем - Услуги).</p>

                    <p>1.2. В соответствии с пунктом 2 статьи 437 Гражданского Кодекса Российской Федерации (ГК РФ) в случае принятия изложенных ниже условий, юридическое или физическое лицо, производящее акцепт настоящей Оферты (далее Заказчик) считается заключившим с Исполнителем договор на условиях, изложенных в настоящей Оферте, в соответствии с пунктом 3 статьи 438 ГК РФ.</p>

                    <p>1.3. Оферта считается акцептованной с момента оформления Заявки на оказание услуг Заказчиком и/или поступления денежных средств Заказчика на расчетный счет Исполнителя или соглашение с настоящей офертой на сайте Исполнителя.</p>

                    <p>1.4. В связи с вышеизложенным, Заказчик подтверждает, что он внимательно прочитал текст данной публичной Оферты. Если Заказчик не согласен с какими-либо условиями настоящей Оферты, он вправе не акцептировать настоящую Оферту. Акцепт настоящей публичной Оферты на иных условиях не допускается.</p>

                    <p>1.5. Договор - оферта не требует скрепления печатями и/или подписания Заказчиком и Исполнителем (далее по тексту - Стороны), сохраняя при этом полную юридическую силу.</p>

                    <p>1.6. Исполнитель и Заказчик гарантируют свою правоспособность и дееспособность, необходимую для заключения и исполнения Договора.</p>

                    <h4><strong>2. ТЕРМИНЫ И ОПРЕДЕЛЕНИЯ</strong></h4>

                    <p>2.1. В настоящей публичной Оферте нижеприведенные термины используются в следующем значении:</p>

                    <p>2.1.1 Оферта - предложение юридическим и физическим лицам заключить с Исполнителем "Договор публичной оферты об оказании платных образовательных услуг по обучению".</p>

                    <p>2.1.2 Акцепт Оферты - полное и безоговорочное принятие Оферты Заказчиком, путем обращения к Исполнителю за оказанием предоставляемых им услуг. Акцепт Оферты означает заключение договора Оферты между Сторонами.</p>

                    <p>2.1.3 Прейскурант (прайс-лист) услуг - действующий систематизированный перечень оказываемых Исполнителем услуг с ценами, опубликованный надлежащим образом.</p>

                    <p>2.2 Текст настоящей Оферты находится у Исполнителя по адресу: г. Нижнекамск ул. Менделеева д.13а или на сайте www.pi-fi.ru</p>

                    <h4><strong>3. Предмет договора.</strong></h4>

                    <p>3.1. Предметом настоящей Оферты является оказание Заказчику услуг по обучению, в соответствии с условиями настоящей Оферты и текущим Прейскурантом услуг Исполнителя.</p>

                    <p>3.2. Виды и наименование занятий, перечень тем, форма реализации занятий, срок оказания Услуг и иные необходимые характеристики занятий, указываются на сайте, а стоимость в прейскуранте Исполнителя.</p>

                    <p>3.3. Исполнитель имеет право в любой момент изменять Прейскурант услуг и условия настоящей публичной Оферты без согласовании с Заказчиком, обеспечивая при этом публикацию измененных условий на сайте. Измененные условия вступают в силу с момента их публикации.</p>

                    <p>3.4. Заказчик подтверждает, что результатом услуг в рамках настоящей Оферты будут являться действия Исполнителя по проведению тематических семинаров, курсов, мастер-классов в очной форме или форме онлайн- трансляции по сети Интернет.</p>

                    <h4><strong>4. Права и обязанности сторон.</strong></h4>

                    <p><strong>4.1. Исполнитель обязуется:</strong></p>

                    <p>4.1.1. В согласованные Сторонами сроки оказать услуги Заказчику надлежащим образом, в соответствии с условиями настоящей Оферты.</p>

                    <p>4.1.2. Не разглашать конфиденциальную информацию и данные, предоставленные Заказчиком в связи с исполнением настоящей Оферты.</p>

                    <p>4.1.3. Проявлять уважение к Заказчику, не нарушать прав Заказчика на свободу совести, информации, на свободное выражение собственных мнений и убеждений.</p>

                    <p><strong>4.2. Исполнитель имеет право:</strong></p>

                    <p>4.2.1. Привлекать для оказания услуг соисполнителей или третьих лиц по своему выбору.</p>

                    <p>4.2.2. Самостоятельно определять формы и методы оказания Услуг с учетом действующего законодательства РФ, а также конкретных условий Оферты.</p>

                    <p>4.2.3. Самостоятельно определять состав специалистов, оказывающих Услуги.</p>

                    <p>4.2.4. По своему усмотрению определять и устанавливать стоимость Услуг.</p>

                    <p>4.2.5. Оказывать Услуги только после внесения Заказчиком предоплаты и акцепта настоящей Оферты.</p>

                    <p>4.2.6. Получать от Заказчика любую информацию, необходимую для исполнения своих обязательств по Оферте. В случае непредоставления либо неполного или неверного предоставления Заказчиком информации, Исполнитель вправе приостановить исполнение своих обязательств до представления необходимой информации в полном объеме.</p>

                    <p>4.2.7. Приостанавливать, ограничивать или прекращать предоставление Услуг Заказчику в любое время с предварительным уведомлением или без такового.</p>

                    <p><strong>4.3. Заказчик обязуется:</strong></p>

                    <p>4.3.1 Своевременно и полностью выплачивать Исполнителю стоимость оказываемых услуг в порядке, в сроки и размере, установленными настоящей Офертой и Прейскурантом услуг.</p>

                    <p>4.3.2. Предоставить Исполнителю все сведения и данные, необходимые для выполнения своих обязательств по настоящей Оферте.</p>

                    <p>4.3.3. Не разглашать конфиденциальную информацию и иные данные, предоставленные Исполнителем в связи с исполнением настоящей Оферты, не раскрывать и не разглашать такие факты или информацию (кроме информации общедоступного характера) какой-либо третьей стороне без предварительного письменного согласия Исполнителя.</p>

                    <p>4.3.4. Самостоятельно обеспечивать техническую возможность пользования Услугами Исполнителя со своей стороны, а именно: - надлежащий доступ в интернет; - наличие программного обеспечения, совместимого с передачей информации от Исполнителя и других необходимых средств.</p>

                    <p>4.3.5. Неукоснительно и безоговорочно соблюдать следующие Правила поведения при получении Услуг (онлайн): - соблюдать дисциплину и общепринятые нормы поведения, в частности, проявлять уважение к персоналу Исполнителя и другим Заказчикам, не посягать на их честь и достоинство; - не допускать агрессивного поведения во время оказания услуг, - не чинить препятствия представителю Исполнителя или другим Заказчикам при оказании /получении Услуг, не допускать высказываний (устно, письменно), не относящихся к теме семинара, курса, мастер-класса и др.; - не использовать информацию, полученную от Исполнителя, способами, которые могут привести или приведут к нанесению ущерба интересам Исполнителя; - не использовать предоставленные Исполнителем материалы с целью извлечения прибыли путем их тиражирования и многократного воспроизведения (публикации в прессе и других изданиях, публичные выступления и т.п.) и иными способами; - не распространять любым способом, в т.ч. третьим лицам, не копировать, не сохранять, не размещать, не публиковать в общедоступных, закрытых, открытых источниках для любого круга лиц (в т.ч. для собственного использования предоставленные Исполнителем: информацию, материалы, методички, записи, видео и т.д. семинаров, курсов, мастер-классов и иных услуг, оказываемых Исполнителем; - не появляться на занятиях с признаками/в состоянии алкогольного, наркотического или иного опьянения и не употреблять алкогольные и наркотические вещества на территории Исполнителя и/или в период оказания Услуг; - не использовать ненормативную лексику, не употреблять в общении выражения, которые могут оскорбить представителя Исполнителя или других Заказчиков; - не распространять рекламу и не предлагать услуги сторонних ресурсов, свои услуги или услуги третьих лиц.</p>

                    <p>4.3.6. Присутствовать на всех согласованных Занятиях.</p>

                    <p><strong>4.4. Заказчик имеет право:</strong></p>

                    <p>4.4.1. Требовать от Исполнителя своевременного и полного оказания услуг в соответствии с условиями настоящей Оферты.</p>

                    <p>4.4.2. Обращаться к Исполнителю по всем вопросам, связанным с оказанием Услуг, а также задавать вопросы, связанные с оказанием Услуг.</p>

                    <p><strong>4.5. Возврат денежных средств</strong></p>

                    <p>4.5.1 Исполнитель возвращает Заказчику стоимость Услуги в случае если это предусмотрено соответствующим тарифом на Услугу Заказчику и если Заказчик письменно заявит об отказе от Услуг Исполнителя и от настоящего Договора, при условии, что такое заявление поступило в адрес ИП Ахтямов А.А. в течение 3 (Трех) дней с момента начала оказания Услуг Исполнителем.</p>

                    <p>4.5.2 Возврат стоимости Услуги Заказчику производится в течение 10 (Десяти) рабочих дней со дня поступления в адрес ИП Ахтямов А.А. заявления Заказчика, на счет, с которого была произведена оплата за Услугу Заказчика. В указанный срок не включен срок проведения банком соответствующей операции по зачислению денежных средств.</p>

                    <p>4.5.3 ИП Ахтямов А.А. вправе вычесть из суммы возвращаемых денежных средств и удержать сумму расходов на операции, связанные с получением и с возвратом стоимости Услуги Заказчика, в том числе комиссии банка.</p>

                    <p>4.5.4 Возврат наличными денежными средствами не производится.</p>

                    <p>4.5.5 Возврат денежных средств, в любом случае не осуществляется при расторжении Договора по основаниям нарушения Заказчиком условий Договора (соответствующая сумма денежных средств удерживается в качестве неустойки).</p>

                    <h4><strong>5. Порядок оказания и сдачи - приемки услуг.</strong></h4>

                    <p>5.1. Заказчик, исходя из действующих цен Исполнителя, самостоятельно определяет количество необходимых ему Занятий и вносит обеспечительный платеж в соответствии с разделом 6 настоящей Оферты.</p>

                    <p>5.2. После внесения обеспечительного платежа посредством перевода денежных средств, Заказчик обязан уведомить об этом Исполнителя по электронной почте на адрес:<a href="mailto:almazpifagorov@gmail.com"><strong>almazpifagorov@gmail.com</strong></a>, направив ему копию платежного поручения или иного документа, подтверждающего произведенную оплату. В случае произведения оплаты посредством электронных платежей Заказчик направляет Исполнителю по адресу, указанному выше сведения по совершенной транзакции включая: ФИО плательщика, No транзакции, дата транзакции, сумма платежа.</p>

                    <p>5.3. Исполнитель проверяет представленные Заказчиком сведения и после поступления денежных средств на расчетный счет Исполнителя, но не ранее получения от Заказчика копии платежного документа о внесении обеспечительного платежа в соответствии с п. 6.5. настоящей Оферты. В случае если денежные средства не поступили по каким-либо причинам на расчетный счет Исполнителя, хотя Заказчик представил документы, подтверждающие оплату, то Исполнитель уведомляет об этом Заказчика и вправе не оказывать услуги по настоящему договору.</p>

                    <p>5.4. Если в установленное графиком время Заказчик не присутствует на занятии в режиме онлайн, занятие считается пропущенным по вине Заказчика, не переносится на другое время и оплачивается Заказчиком в размере 100% в соответствии с п. 2. ст. 781 ГК РФ</p>

                    <p>5.5. Занятия, которые не состоялись по вине Исполнителя, переносятся без их потери на другое время с аналогичной тарификацией.</p>

                    <p>5.6. Заказчик, понимает и осознает, что, Исполнитель вправе произвести замену Преподавателя на равноценного.</p>

                    <p>5.7. Претензии Заказчика по объему и качеству оказанных услуг должны быть обоснованными и содержать конкретные ссылки на несоответствие результатов оказанных услуг настоящему договору. При этом Стороны письменно согласовывают сроки и условия устранения замечаний Заказчика</p>

                    <h4><strong>6. Оплата услуг.</strong></h4>

                    <p>6.1. Стоимость услуг рассчитывается по утвержденным расценкам - прейскуранту услуг, прайс-листу и фиксируется в выписываемой Заказчику Исполнителем квитанции на бланке строгой отчетности или в квитанции Исполнителя, выставляемой Заказчику через сайт для оплаты через банк, а также - в счете, выставляемом Заказчику - юридическому лицу.</p>

                    <p>6.2. Оплата услуг по настоящей Оферте производится по факту оказания услуг, в размере 100% от стоимости оказываемых услуг в срок не позднее 2 (двух) дней до дня оказания услуг.</p>

                    <p>6.3. Стороны пришли к соглашению, что в соответствии с п. 1 ст. 329 ГК РФ в качестве способа обеспечения исполнения Заказчиком обязательств по настоящему договору, в том числе и по оплате занятий, Стороны устанавливают обеспечительные меры в виде обеспечительного взноса в размере равном оплате за обучение, который должен быть внесен Заказчиком до начала Занятий. Исполнитель по окончании обучения вправе удержать из обеспечительного взноса сумму, необходимую на покрытие оплаты, оказываемых Исполнителем услуг по настоящему договору. В случае если Заказчик отказывается от исполнения своих обязательств по настоящему договору и прекращает обучение, то сумма обеспечительного взноса не подлежит возврату Заказчику.</p>

                    <p>6.4. Заказчик вносит обеспечительный платеж путем перечисления денежных средств на расчетный счет Исполнителя с обязательным представлением квитанции банка, платежного поручения, либо наличным платежом в любом из офисов Исполнителя в г. Казань.</p>

                    <p>6.5. При оплате по настоящему договору Заказчик обязан указывать назначения платежа.</p>

                    <p>6.6. Датой исполнения обязательств по перечислению денежных средств, считается дата поступления денежных средств на расчетный счет Исполнителя, либо в кассу Заказчика.</p>

                    <h4><strong>7. Ответственность сторон.</strong></h4>

                    <p>7.1. За неисполнение или ненадлежащее исполнение обязательств по настоящему Договору Стороны несут ответственность в соответствии с действующим законодательством РФ.</p>

                    <p>7.2. Исполнитель не несет ответственности за невозможность оказания Услуг Исполнителем /принятием Услуг Заказчиком, если такая невозможность возникла вследствие нарушения работы в сети Интернет, программного обеспечения или оборудования Заказчика.</p>

                    <p>7.3. Любые требования Заказчика рассматриваются только на основании обоснованного письменного требования, направленного Исполнителю по адресам, указанным в настоящей Оферте.</p>

                    <p>7.4. В случае нарушения Заказчиком любого из обязательств Заказчика, предусмотренных Офертой, Исполнитель вправе отказаться от исполнения Оферты и расторгнуть договор.</p>

                    <p>7.5. Претензионный порядок рассмотрения споров и разногласий, возникающих и (или) связанных c настоящим договором является для Сторон обязательным.</p>

                    <p>7.6. Претензии направляются Сторонами нарочным либо заказным почтовым отправлением с уведомлением о вручении последнего адресату по месту нахождения/проживания/регистрации (адресу для корреспонденции) Стороны, указанному в разделе 12 договора.</p>

                    <p>7.7. Направление Сторонами претензии иным способом, чем указано в п.7.6. договора не допускается</p>

                    <p>7.8. Срок рассмотрения претензии составляет 30 рабочих дней со дня получения претензии адресатом.</p>

                    <p>7.9. Все споры, разногласия или требования, возникающие из настоящего договора или в связи с ним, в том числе касающиеся его исполнения, нарушения, прекращения или недействительности, подлежат разрешению (с учетом правил подсудности и подведомственности) в Арбитражном суде Республики Татарстан.</p>

                    <h4><strong>8. Основания расторжения договора</strong></h4>

                    <p>8.1. Оферта вступает в силу с момента оплаты Заказчиком Услуг Исполнителя способами, указанными в настоящей Оферте и на сайте Исполнителя, и действует до полного исполнения Сторонами своих обязательств.</p>

                    <p>8.2. Исполнитель оставляет за собой право внести изменения в условия Оферты и/или отозвать Оферту в любой момент по своему усмотрению. В случае внесения изменений в Оферту, такие изменения вступают в силу с момента опубликования на Сайте, если иной срок вступления в силу не установлен или не определен при опубликовании изменений Оферты.</p>

                    <p>8.3. Заказчик не вправе расторгнуть Оферту и/или требовать возврата стоимости Услуг по любому основанию после начала их оказания (не зависимо от посещения или непосещения курсов, семинаров, мастер- классов и др).</p>

                    <p>8.4. Исполнитель вправе отказаться от Оферты (исполнения Оферты) и прекратить оказание Услуг в случае нарушения Заказчиком условий настоящей Оферты, в т.ч. любых условий, предусмотренных в п.4.3 настоящей Оферты, не возвращая оплаченные денежные средства Заказчику. Несоблюдение Правил определяется Заказчиком, в том числе в лице представителя Исполнителя, непосредственно проводящим семинар, курс или мастер-класс.</p>

                    <h4><strong>9. Форс-мажор</strong></h4>

                    <p>9.1. Стороны освобождаются от ответственности за полное или частичное неисполнение обязательств по Оферте в случае, если неисполнение обязательств явилось следствием действий непреодолимой силы, а именно: пожара, наводнения, землетрясения, забастовки, войны, действий органов государственной власти или других независящих от Сторон обстоятельств.</p>

                    <p>9.2. Сторона, которая не может выполнить обязательства по Оферте, должна своевременно, но не позднее пяти календарных дней после наступления обстоятельств непреодолимой силы, письменно известить другую Сторону, с предоставлением обосновывающих документов, выданных компетентными органами.</p>

                    <p>9.3. Исполнитель не несет ответственности за временные сбои и перерывы в работе интернет ресурсов Исполнителя и вызванную ими потерю информации.</p>

                    <h4><strong>10. Прочие условия</strong></h4>

                    <p>10.1. Стороны признают, что, если какое-либо из положений Оферты становится недействительным в течение срока его действия вследствие изменения законодательства, остальные положения Оферты обязательны для Сторон в течение срока действия Оферты.</p>

                    <p>10.2. Исполнитель не несет ответственности за результат использования или полезность оказываемых Услуг. В случае несоответствия состава Услуг, предоставляемых в рамках действующего Договора-оферты, потребностям Заказчика, Исполнитель ответственности не несет.</p>

                    <h4><strong>11. Согласие на обработку персональных данных</strong></h4>

                    <p>11.1 Акцептуя настоящую оферту, Заказчик выражает согласие и разрешает Исполнителю обрабатывать свои персональные данные, в том числе фамилию, имя, отчество, дату рождения, пол, место работы и должность, почтовый адрес; домашний, рабочий, мобильный телефоны, адрес электронной почты, включая сбор, систематизацию, накопление, хранение, уточнение (обновление, изменение), использование, распространение (в том числе передачу на территории Российской Федерации и трансграничную передачу), обезличивание, блокирование, уничтожение персональных данных, а также передачу их контрагентам Исполнителя с целью дальнейшей обработки в целях качественного оказания услуг по договору, а также проведения исследований, направленных на улучшение качества услуг, маркетинговых программ, статистических исследований, а также для продвижения услуг на рынке путем осуществления прямых контактов с Заказчиком с помощью различных средств связи, включая, но, не ограничиваясь: почтовая рассылка, электронная почта, телефон.</p>

                    <p>11.2 Заказчик выражает согласие и разрешает Исполнителю и контрагентам Исполнителя обрабатывать свои персональные данные, с помощью автоматизированных систем управления базами данных, а также иных программных средств, специально разработанных по поручению Заказчика.</p>

                    <p>11.3 Заказчик соглашается с тем, что, если это необходимо для реализации целей, указанных в настоящей оферте, его персональные данные, полученные Исполнителем, могут быть переданы третьим лицам, которым Исполнитель может поручить обработку персональных данных Заказчика на основании договора, заключенного с такими лицами. При передаче данных Заказчика, Исполнитель предупреждает лиц, получающих персональные данные Заказчика о том, что эти данные являются конфиденциальными и могут быть использованы лишь в целях, для которых они сообщены, и требует от этих лиц соблюдения этого правила.</p>

                    <p>11.4 Заказчик вправе запросить у Исполнителя полную информацию о своих персональных данных, их обработке и использовании, а также потребовать исключения или исправления/дополнения неверных или неполных персональных данных, отправив соответствующий письменный запрос на электронный адрес Исполнителя.</p>

                    <p>11.5 Данное Заказчиком согласие на обработку его персональных данных является бессрочным и может быть отозвано посредством направления Заказчиком письменного заявления на адрес Исполнителя.</p>

                    <h4><strong>12. Реквизиты Исполнителя.</strong></h4>

                    <p>Индивидуальный предприниматель</p>
                    <p>Ахтямов Алмаз Азатович</p>
                    <p>ИНН:165125012795</p>
                    <p>ОГРНИП: 318169000221595</p>
                    <p>Юридический адрес: 423571 г. Нижнекамск ул. Менделеева д.13а</p>
                    <p>Банковские реквизиты:</p>
                    <p>Р/С 40817810000004258244</p>
                    <p>К/С 30101810145250000974;</p>
                    <p>Банк: АО «Тинькофф Банк»</p>
                    <p>БИК:044525974</p>
                    <p>
                        Обновлено «01» января 2021г.
                    </p>
                </div>

                <?php \yii\bootstrap\Modal::end() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <p class="copy footer__copy"><span class="copy__name">Образовательная платформа «Pi-Fi».</span><span> <?= date('Y') ?> </span><span class="copy__symbol">&copy;</span><span> Все права защищены </span></p>
            </div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
