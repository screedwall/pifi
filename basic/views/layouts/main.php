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
                    '<li class="dropdown-header">Курсы, месяца, уроки</li>',
                    ['label' => 'Курсы', 'url' => ['/admin/courses']],
                    ['label' => 'Купоны', 'url' => ['/admin/coupons']],
                    '<li class="dropdown-header">Пользователи</li>',
                    ['label' => 'Пользователи', 'url' => ['/admin/users']],
                    ['label' => 'Преподаватели', 'url' => ['/admin/teachers']],
                ],
            ]
            ) : (
            ''
            )),
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
            <div class="col-md-4 text-center">
                <a href="https://www.instagram.com/pifi_school/" target="_blank" class="socials__link"><img src="/img/instagram-icon.svg" alt="instagram" class="socials__icon"></a>
                <a href="https://www.youtube.com/channel/UCY1a1U9BAQ2lTg_DnuhLb7A" target="_blank" class="socials__link"><img src="/img/youtube-icon.svg" alt="youtube" class="socials__icon"></a>
                <a href="https://vk.com/pifi_school" target="_blank" class="socials__link"><img src="/img/vk-icon.svg" alt="vk" class="socials__icon"></a>
            </div>
            <div class="col-md-6 text-right">
                <?php \yii\bootstrap\Modal::begin([
                    'header' => '<h2>ИП Ахтямов А.А.</h2>',
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
                    <h2>ДОГОВОР ОФЕРТЫ</h2>
                    <p>
                        Текст настоящего пользовательского соглашения, постоянно размещенный в сети Интернет по сетевому адресу pi-fi.ru, содержит все существенные условия соглашения и является предложением ИП Ахтямов А.А. заключить соглашение с третьим лицом, использующим сайт в сети Интернет pi-fi.ru на указанных в тексте соглашения условиях (далее – Пользователь). Таким образом, текст настоящего пользовательского соглашения является публичной офертой в соответствии с пунктом 2 статьи 437 Гражданского Кодекса Российской Федерации.
                    </p>
                    <h2>ПОЛЬЗОВАТЕЛЬСКОЕ СОГЛАШЕНИЕ</h2>
                    <p></p>
                    <p>
                        1. ОБЩИЕ ПОЛОЖЕНИЯ
                    </p>
                    <p>
                        Российская Федерация, г. Нижнекамск                    «01» января 2018г.
                        1.1.    Настоящее Пользовательское соглашение (далее – Соглашение) относится к сайту Информационного ресурса «Pi-Fi», расположенному
                        по адресу pi-fi.ru, и ко всем соответствующим сайтам, связанным с сайтом www.pi-fi.ru.
                    </p>
                    <p style="font-weight: bold;">
                        1.2. Информационный ресурс «Pi-Fi» (далее – Сайт) является собственностью ИП Ахтямов Алмаз Азатович, ИНН 165125012795, юридический и фактический адрес г. Нижнекамск ул. Менделеева д.13а, телефон +79274880526, электронная почта almazpifagorov@gmail.com , ОГРНИП 318169000221595
                    </p>
                    <p>
                        1.3.    Настоящее Соглашение регулирует отношения между Администрацией сайта  Информационного ресурса «Pi-Fi»  (далее – Администрация сайта) и Пользователем данного Сайта.
                    </p>
                    <p>
                        1.4. Администрация сайта оставляет за собой право в любое время изменять, добавлять или удалять пункты настоящего Соглашения без уведомления Пользователя.
                    </p>
                    <p>
                        1.5. Продолжение использования Сайта Пользователем означает принятие Соглашения и изменений, внесенных в настоящее Соглашение.
                    </p>
                    <p>
                        1.6. Пользователь несет персональную ответственность за проверку настоящего Соглашения на наличие изменений в нем.
                    </p>
                    <p>
                        2.  ОПРЕДЕЛЕНИЯ ТЕРМИНОВ
                    </p>
                    <p>
                        2.1.    Перечисленные ниже термины имеют для целей настоящего Соглашения следующее значение:
                    </p>
                    <p>
                        2.1.1 «Pi-Fi» – Информационный ресурс, расположенный на доменном имени www.pi-fi.ru, осуществляющий свою деятельность посредством Интернет-ресурса и сопутствующих ему сервисов.
                    </p>
                    <p>
                        2.1.2. Информационный ресурс – сайт, содержащий информацию о предлагаемых контрагентом информационных услугах, позволяющий выбрать и приобрести эти услуги.
                    </p>
                    <p>
                        2.1.3. Администрация сайта Информационного ресурса – уполномоченные сотрудники на управления Сайтом, действующие от имени «Pi-Fi».
                    </p>
                    <p>
                        2.1.4. Пользователь сайта Информационного ресурса  (далее ‑ Пользователь) – лицо, имеющее доступ к Сайту, посредством сети Интернет и использующее Сайт.
                    </p>
                    <p>
                        2.1.5. Информационная услуга - услуга предоставляемая Пользователю  Администрацией сайта  на платной основе.
                    </p>
                    <p>
                        2.1.6. Содержание сайта Информационного ресурса (далее – Содержание) - охраняемые результаты интеллектуальной деятельности, включая тексты литературных произведений, их названия, предисловия, аннотации, статьи, иллюстрации, обложки, музыкальные произведения с текстом или без текста, графические, текстовые, фотографические, производные, составные и иные произведения, пользовательские интерфейсы, визуальные интерфейсы, названия товарных знаков, логотипы, программы для ЭВМ, базы данных, а также дизайн, структура, выбор, координация, внешний вид, общий стиль и расположение данного Содержания, входящего в состав Сайта и другие объекты интеллектуальной собственности все вместе и/или по отдельности, содержащиеся на сайте Информационного ресурса и связанных с ним сайтов.
                    </p>
                    <p>
                        3.  ПРЕДМЕТ СОГЛАШЕНИЯ
                    </p>
                    <p>
                        3.1. Предметом настоящего Соглашения является предоставление Пользователю Информационного ресурса доступа к оказываемым на Сайте Информационным услугам.
                    </p>
                    <p>
                        3.1.1. Интернет-магазин предоставляет Пользователю следующие виды услуг (сервисов):
                    </p>
                    <p>
                        •   доступ к электронному контенту на  платной основе, с правом приобретения (скачивания), просмотра контента;
                        <br>
                        •   доступ к средствам поиска и навигации Информационного ресурса;
                        <br>
                        •   предоставление Пользователю возможности размещения сообщений, комментариев, рецензий Пользователей, выставления оценок контенту Информационного ресурса;
                        <br>
                        •   доступ к информации об Услугах и к информации о их приобретении на  платной основе;
                        <br>
                        •   иные виды услуг (сервисов), реализуемые на страницах Интернет-магазина.
                    </p>
                    <p>
                        3.1.2. Под действие настоящего Соглашения подпадают все существующие (реально функционирующие) на данный момент услуги (сервисы) Информационного ресурса, а также любые их последующие модификации и появляющиеся в дальнейшем дополнительные услуги (сервисы) Информационного ресурса.
                    </p>
                    <p>
                        3.2. Доступ к Информационным услугам сайта предоставляется платной на основе.
                    </p>
                    <p>
                        3.3. Настоящее Соглашение является публичной офертой. Получая доступ к Сайту Пользователь считается присоединившимся к настоящему Соглашению.
                    </p>
                    <p>
                        3.4. Использование материалов и сервисов Сайта регулируется нормами действующего законодательства Российской Федерации
                    </p>
                    <p>
                        4.  ПРАВА И ОБЯЗАННОСТИ СТОРОН
                    </p>
                    <p>
                        4.1. Администрация сайта вправе:
                    </p>
                    <p>
                        4.1.1. Изменять правила пользования Сайтом, а также изменять содержание данного Сайта. Изменения вступают в силу с момента публикации новой редакции Соглашения на Сайте.
                    </p>
                    <p>
                        4.1.2. Ограничить доступ к Сайту в случае нарушения Пользователем условий настоящего Соглашения.
                    </p>
                    <p>
                        4.1.3. Изменять размер оплаты, взимаемый за предоставление доступа к использованию сайта Информационного ресурса. Изменение стоимости не будет распространяться на Пользователей, имеющих регистрацию к моменту изменения размера оплаты, за исключением случаев, особо оговоренных Администрацией сайта Информационного ресурса.
                    </p>
                    <p>
                        4.2. Пользователь вправе:
                    </p>
                    <p>
                        4.2.1. Получить доступ к использованию Сайта после соблюдения требований о регистрации и оплате.
                    </p>
                    <p>
                        4.2.2. Пользоваться всеми имеющимися на Сайте услугами, а также приобретать любые Информационные услуги, предлагаемые на Сайте.
                    </p>
                    <p>
                        4.2.3. Задавать любые вопросы, относящиеся к услугам Интернет-магазина по реквизитам, которые находятся в пункте 1.2 настоящего положения.
                    </p>
                    <p>
                        4.2.4. Пользоваться Сайтом исключительно в целях и порядке, предусмотренных Соглашением и не запрещенных законодательством Российской Федерации.
                    </p>
                    <p style="font-weight: bold;">
                        4.2.5. Получить возврат стоимости Информационной услуги в двухнедельный срок со дня обращения к Администрации Сайта, при условии если ее не оказали, за вычетом издержек и комиссий связанных переводом денежных средств Пользователю. Для возврата средств Пользователю необходимо связаться с Администрацией Сайта используя данные указанные в пункте 1.2. настоящего соглашения.
                    </p>
                    <p>
                        4.3. Пользователь Сайта обязуется:
                    </p>
                    <p>
                        4.3.1. Предоставлять по запросу Администрации сайта дополнительную информацию, которая имеет непосредственное отношение к предоставляемым услугам данного Сайта.
                    </p>
                    <p>
                        4.3.2. Соблюдать имущественные и неимущественные права авторов и иных правообладателей при использовании Сайта.
                    </p>
                    <p>
                        4.3.3. Не предпринимать действий, которые могут рассматриваться как нарушающие нормальную работу Сайта.
                    </p>
                    <p>
                        4.3.4. Не распространять с использованием Сайта любую конфиденциальную и охраняемую законодательством Российской Федерации информацию о физических либо юридических лицах.
                    </p>
                    <p>
                        4.3.5. Избегать любых действий, в результате которых может быть нарушена конфиденциальность охраняемой законодательством Российской Федерации информации.
                    </p>
                    <p>
                        4.3.6. Не использовать Сайт для распространения информации рекламного характера, иначе как с согласия Администрации сайта.
                    </p>
                    <p>
                        4.3.7. Не использовать сервисы сайта Информационного ресурса с целью:
                    </p>
                    <p>
                        4.3.7. 1. загрузки контента, который является незаконным, нарушает любые права третьих лиц; пропагандирует насилие, жестокость, ненависть и (или) дискриминацию по расовому, национальному, половому, религиозному, социальному признакам; содержит недостоверные сведения и (или) оскорбления в адрес конкретных лиц, организаций, органов власти.
                    </p>
                    <p>
                        4.3.7. 2. побуждения к совершению противоправных действий, а также содействия лицам, действия которых направлены на нарушение ограничений и запретов, действующих на территории Российской Федерации.
                    </p>
                    <p>
                        4.3.7. 3. нарушения прав несовершеннолетних лиц и (или) причинение им вреда в любой форме.
                    </p>
                    <p>
                        4.3.7. 4. ущемления прав меньшинств.
                    </p>
                    <p>
                        4.3.7. 5. некорректного сравнения Информационной услуги, а также формирования негативного отношения к лицам, (не) пользующимся определенными Информационными услугами, или осуждения таких лиц.
                    </p>
                    <p>
                        4.4. Пользователю запрещается:
                    </p>
                    <p>
                        4.4.1. Использовать любые устройства, программы, процедуры, алгоритмы и методы, автоматические устройства или эквивалентные ручные процессы для доступа, приобретения, копирования или отслеживания содержания Сайта данного Информационного ресурса;
                    </p>
                    <p>
                        4.4.2. Нарушать надлежащее функционирование Сайта;
                    </p>
                    <p>
                        4.4.3. Любым способом обходить навигационную структуру Сайта для получения или попытки получения любой информации, документов или материалов любыми средствами, которые специально не представлены сервисами данного Сайта;
                    </p>
                    <p>
                        4.4.4. Несанкционированный доступ к функциям Сайта, любым другим системам или сетям, относящимся к данному Сайту, а также к любым услугам, предлагаемым на Сайте;
                    </p>
                    <p>
                        4.4.4. Нарушать систему безопасности или аутентификации на Сайте или в любой сети, относящейся к Сайту.
                    </p>
                    <p>
                        4.4.5. Выполнять обратный поиск, отслеживать или пытаться отслеживать любую информацию о любом другом Пользователе Сайта.
                    </p>
                    <p>
                        4.4.6. Использовать Сайт и его Содержание в любых целях, запрещенных законодательством Российской Федерации, а также подстрекать к любой незаконной деятельности или другой деятельности, нарушающей права интернет-магазина или других лиц.
                    </p>
                    <p>
                        5.  ИСПОЛЬЗОВАНИЕ САЙТА ИНФОРМАЦИОННОГО РЕСУРСА
                    </p>
                    <p>
                        5.1. Сайт и Содержание, входящее в состав Сайта, принадлежит и управляется Администрацией сайта.
                    </p>
                    <p>
                        5.2. Содержание Сайта не может быть скопировано, опубликовано, воспроизведено, передано или распространено любым способом, а также размещено в глобальной сети «Интернет» без предварительного письменного согласия Администрации сайта.
                    </p>
                    <p>
                        5.3. Содержание Сайта защищено авторским правом, законодательством о товарных знаках, а также другими правами, связанными с интеллектуальной собственностью, и законодательством о недобросовестной конкуренции.
                    </p>
                    <p>
                        5.4. Приобретение Информационной услуги, предлагаемой на Сайте, может потребовать создания учётной записи Пользователя.
                    </p>
                    <p>
                        5.5. Пользователь несет персональную ответственность за сохранение конфиденциальности информации учётной записи, включая пароль, а также за всю без исключения деятельность, которая ведётся от имени Пользователя учётной записи.
                    </p>
                    <p>
                        5.6. Пользователь должен незамедлительно уведомить Администрацию сайта о несанкционированном использовании его учётной записи или пароля или любом другом нарушении системы безопасности.
                    </p>
                    <p>
                        5.7. Администрация сайта обладает правом в одностороннем порядке аннулировать учетную запись Пользователя, если она не использовалась более 2 календарных месяцев подряд без уведомления Пользователя.
                    </p>
                    <p>
                        5.7. Настоящее Соглашение распространяет свое действия на все дополнительные положения и условия о покупке Информационных услуг, предоставляемых на Сайте.
                    </p>
                    <p>
                        5.8. Информация, размещаемая на Сайте не должна истолковываться как изменение настоящего Соглашения.
                    </p>
                    <p>
                        5.9. Администрация сайта имеет право в любое время без уведомления Пользователя вносить изменения в перечень Информационных услуг, предлагаемых на Сайте, и (или) в цены, применимые к таким Информационным услугам по их реализации и (или) оказываемым услугам.
                    </p>
                    <p>
                        6.  ОТВЕТСТВЕННОСТЬ
                    </p>
                    <p>
                        6.1. Любые убытки, которые Пользователь может понести в случае умышленного или неосторожного нарушения любого положения настоящего Соглашения, а также вследствие несанкционированного доступа к коммуникациям другого Пользователя, Администрацией сайта не возмещаются.
                    </p>
                    <p>
                        6.2. Администрация сайта не несет ответственности за:
                    </p>
                    <p>
                        6.2.1. Задержки или сбои в процессе совершения операции, возникшие вследствие непреодолимой силы, а также любого случая неполадок в телекоммуникационных, компьютерных, электрических и иных смежных системах.
                    </p>
                    <p>
                        6.2.2. Действия систем переводов, банков, платежных систем и за задержки связанные с их работой.
                    </p>
                    <p>
                        6.2.3. Надлежащее функционирование Сайта, в случае, если Пользователь не имеет необходимых технических средств для его использования, а также не несет никаких обязательств по обеспечению пользователей такими средствами.
                    </p>
                    <p>
                        6.2.4. Администрация сайта обязуется вернуть Пользователю стоимость Информационной услуги за вычетом издержек и комиссий связанных переводом этих средств Пользователю, при неоказании Информационной услуги в двухнедельный срок с момента обращения пользователя к Администрации сайта.
                    </p>
                    <p>
                        7.  НАРУШЕНИЕ УСЛОВИЙ ПОЛЬЗОВАТЕЛЬСКОГО СОГЛАШЕНИЯ
                    </p>
                    <p>
                        7.1. Администрация сайта вправе раскрыть любую собранную о Пользователе данного Сайта информацию, если раскрытие необходимо в связи с расследованием или жалобой в отношении неправомерного использования Сайта либо для установления (идентификации) Пользователя, который может нарушать или вмешиваться в права Администрации сайта или в права других Пользователей Сайта.
                    </p>
                    <p>
                        7.2. Администрация сайта имеет право раскрыть любую информацию о Пользователе, которую посчитает необходимой для выполнения положений действующего законодательства или судебных решений, обеспечения выполнения условий настоящего Соглашения, защиты прав или безопасности Pi-Fi, Пользователей.
                    </p>
                    <p>
                        7.3. Администрация сайта имеет право раскрыть информацию о Пользователе, если действующее законодательство Российской Федерации требует или разрешает такое раскрытие.
                    </p>
                    <p>
                        7.4. Администрация сайта вправе без предварительного уведомления Пользователя прекратить и (или) заблокировать доступ к Сайту, если Пользователь нарушил настоящее Соглашение или содержащиеся в иных документах условия пользования Сайтом, а также в случае прекращения действия Сайта либо по причине технической неполадки или проблемы.
                    </p>
                    <p>
                        7.5. Администрация сайта не несет ответственности перед Пользователем или третьими лицами за прекращение доступа к Сайту в случае нарушения Пользователем любого положения настоящего Соглашения или иного документа, содержащего условия пользования Сайтом.
                    </p>
                    <p>
                        8.  РАЗРЕШЕНИЕ СПОРОВ
                    </p>
                    <p>
                        8.1. В случае возникновения любых разногласий или споров между Сторонами настоящего Соглашения обязательным условием до обращения в суд является предъявление претензии (письменного предложения о добровольном урегулировании спора).
                    </p>
                    <p>
                        8.2. Получатель претензии в течение 30 календарных дней со дня ее получения, письменно уведомляет заявителя претензии о результатах рассмотрения претензии.
                    </p>
                    <p>
                        8.3. При невозможности разрешить спор в добровольном порядке любая из Сторон вправе обратиться в суд за защитой своих прав, которые предоставлены им действующим законодательством Российской Федерации.
                    </p>
                    <p>
                        8.4. Любой иск в отношении условий использования Сайта должен быть предъявлен в течение 1 месяца после возникновения оснований для иска, за исключением защиты авторских прав на охраняемые в соответствии с законодательством материалы Сайта. При нарушении условий данного пункта любой иск или основания для иска погашаются исковой давностью.
                    </p>
                    <p>
                        9. ДОПОЛНИТЕЛЬНЫЕ УСЛОВИЯ
                    </p>
                    <p>
                        9.1. Администрация сайта не принимает встречные предложения от Пользователя относительно изменений настоящего Пользовательского соглашения.
                    </p>
                    <p>
                        9.2. Отзывы Пользователя, размещенные на Сайте, не являются конфиденциальной информацией и могут быть использованы Администрацией сайта без ограничений.
                    </p>
                    <p>
                        Обновлено «01» января 2019г.
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
