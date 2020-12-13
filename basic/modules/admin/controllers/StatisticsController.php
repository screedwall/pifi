<?php

namespace app\modules\admin\controllers;

use app\models\TinkoffPay;
use yii\helpers\ArrayHelper;
use Yii;

class StatisticsController extends \yii\web\Controller
{

    public function actionIndex()
    {
        $lessonsQuery = TinkoffPay::find()
            ->select(['date' => 'date_trunc(\'day\', "createdAt")', 'courseId', 'SUM(amount)', 'count' => 'SUM(CAST(CONCAT(\'1\', \' \') as INTEGER))'])
            ->where(['status' => 'CONFIRMED'])
            ->groupBy(['date', 'courseId'])
            ->orderBy('date')
            ->with('course')
            ->asArray()
            ->all();

        return $this->render('index', [
            'locales' => $this->getLocales(),
            'soldLessons' => $this->getSoldLessons($lessonsQuery),
            'proceedsLessons' => $this->getProceedsLessons($lessonsQuery),
            'online' => $this->getOnline(),
        ]);
    }

    private function getLocales()
    {
        return [
                    [
                        "name" => "ru",
                        "options" => [
                            "months" => [
                                "Январь",
                                "Февраль",
                                "Март",
                                "Апрель",
                                "Май",
                                "Июнь",
                                "Июль",
                                "Август",
                                "Сентябрь",
                                "Октябрь",
                                "Ноябрь",
                                "Декабрь"
                            ],
                            "shortMonths" => [
                                "Янв",
                                "Фев",
                                "Мар",
                                "Апр",
                                "Май",
                                "Июн",
                                "Июл",
                                "Авг",
                                "Сен",
                                "Окт",
                                "Ноя",
                                "Дек"
                            ],
                            "days" => [
                                "Воскресенье",
                                "Понедельник",
                                "Вторник",
                                "Среда",
                                "Четверг",
                                "Пятница",
                                "Суббота"
                            ],
                            "shortDays" => ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
                            "toolbar" => [
                                "exportToSVG" => "Сохранить SVG",
                                "exportToPNG" => "Сохранить PNG",
                                "exportToCSV" => "Сохранить CSV",
                                "menu" => "Меню",
                                "selection" => "Выбор",
                                "selectionZoom" => "Выбор с увеличением",
                                "zoomIn" => "Увеличить",
                                "zoomOut" => "Уменьшить",
                                "pan" => "Перемещение",
                                "reset" => "Сбросить увеличение"
                            ]
                        ]
                    ]
                ];
    }

    private function getSoldLessons($lessonsQuery)
    {
        foreach ($lessonsQuery as &$lesson)
        {
            $lesson['course'] = $lesson['course']['examType'];
        }

        $courses = ArrayHelper::index($lessonsQuery, null, 'course');

        $soldLessons = [];
        foreach ($courses as $course)
        {
            $data = [];
            foreach ($course as $resource)
                array_push($data, [
                    $resource['date'],
                    $resource['count'],
                ]);

            array_push($soldLessons, [
                'name' => $resource['course'],
                'data' => $data,
            ]);
        }

        return $soldLessons;
    }

    private function getProceedsLessons($lessonsQuery)
    {
        foreach ($lessonsQuery as &$lesson)
        {
            $lesson['course'] = $lesson['course']['examType'];
        }

        $courses = ArrayHelper::index($lessonsQuery, null, 'course');

        $soldLessons = [];
        foreach ($courses as $course)
        {
            $data = [];
            foreach ($course as $resource)
                array_push($data, [
                    $resource['date'],
                    $resource['sum'],
                ]);

            array_push($soldLessons, [
                'name' => $resource['course'],
                'data' => $data,
            ]);
        }

        return $soldLessons;
    }

    public function getOnline()
    {
        $redis = Yii::$app->redis;
        $anons = count($redis->keys('anon:*'));
        $users = count($redis->keys('user:*')); //plus current user

        return ['anons' => $anons, 'users' => $users];
    }
}
