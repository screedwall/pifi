<?php
/* @var $this yii\web\View */
/* @var $locales array */
/* @var $soldLessons array */
/* @var $proceedsLessons array */
/* @var $online array */

use onmotion\apexcharts\ApexchartsWidget;

$currentOnline = $online['users']+$online['anons'];

$js = <<<SCRIPT
/* To initialize BS3 tooltips set this below */
$(function () { 
    $("[data-toggle='tooltip']").tooltip(); 
});;
/* To initialize BS3 popovers set this below */
$(function () { 
    $("[data-toggle='popover']").popover(); 
});
SCRIPT;
// Register tooltip/popover initialization javascript
$this->registerJs($js);
?>
<h1>Pi-Fi статистика</h1>

<h3>Текущий онлайн: <?= $currentOnline ?> <span data-toggle="tooltip" title="Анонимных пользователей: <?=$online['anons']?>" class="<?= $currentOnline > 0 ? 'dot-enabled' : 'dot-disabled' ?>"></span></h3>

<?php

$series = [
    [
        'name' => 'Entity 1',
        'data' => [
            ['2018-10-04', 4.66],
            ['2018-10-05', 5.0],
        ],
    ],
    [
        'name' => 'Entity 2',
        'data' => [
            ['2018-10-04', 3.88],
            ['2018-10-05', 3.77],
        ],
    ],
    [
        'name' => 'Entity 3',
        'data' => [
            ['2018-10-04', 4.40],
            ['2018-10-05', 5.0],
        ],
    ],
    [
        'name' => 'Entity 4',
        'data' => [
            ['2018-10-04', 4.5],
            ['2018-10-05', 4.18],
        ],
    ],
];
echo ApexchartsWidget::widget([
    'type' => 'line', // default area
    'chartOptions' => [
        'chart' => [
            'id' => 'count',
            'group' => 'sales',
            'toolbar' => [
                'show' => true,
                'autoSelected' => 'zoom',
            ],
            'locales' => $locales,
            'defaultLocale' => "ru",
        ],
        'title' => [
            'text' => 'Продажи курсов по дням',
        ],
        'xaxis' => [
            'type' => 'datetime',
            'title' => [
                'text' => 'Период',
                'offsetY' => 15,
            ],
        ],
        'yaxis' => [
            'title' => [
                'text' => 'Продано курсов',
            ],
            'min' => 0,
            'forceNiceScale' => true,
        ],
        'dataLabels' => [
            'enabled' => true,
        ],
        'stroke' => [
            'curve' => 'smooth',
        ],
        'grid' => [
            'row' => [
                'colors' => ['#f3f3f3', 'transparent'],
                'opacity' => 0.5,
            ],
        ],
        'legend' => [
            'verticalAlign' => 'bottom',
            'horizontalAlign' => 'left',
        ],
    ],
    'series' => $soldLessons,
]);

echo ApexchartsWidget::widget([
    'type' => 'line', // default area
    'chartOptions' => [
        'chart' => [
            'id' => 'sum',
            'group' => 'sales',
            'toolbar' => [
                'show' => true,
                'autoSelected' => 'zoom',
            ],
            'locales' => $locales,
            'defaultLocale' => "ru",
        ],
        'title' => [
            'text' => 'Выручка по дням',
        ],
        'xaxis' => [
            'type' => 'datetime',
            'title' => [
                'text' => 'Период',
                'offsetY' => 15,
            ],
        ],
        'yaxis' => [
            'title' => [
                'text' => 'Сумма продаж',
            ],
            'min' => 0,
            'forceNiceScale' => true,
        ],
        'dataLabels' => [
            'enabled' => true,
        ],
        'stroke' => [
            'curve' => 'smooth',
        ],
        'grid' => [
            'row' => [
                'colors' => ['#f3f3f3', 'transparent'],
                'opacity' => 0.5,
            ],
        ],
        'legend' => [
            'verticalAlign' => 'bottom',
            'horizontalAlign' => 'left',
        ],
    ],
    'series' => $proceedsLessons,
]);