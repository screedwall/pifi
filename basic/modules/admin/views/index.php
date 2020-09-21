<?php
use yii\bootstrap\Tabs;

echo Tabs::widget([
    'items' => [
        [
            'label' => 'Чето',
            'content' => 'Example',
            'active' => true
        ],
        [
            'label' => 'Курсы',
            'content' => $this->render('courses/index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider
            ]),
        ],
    ],
]);