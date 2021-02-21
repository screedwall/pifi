<?php


namespace app\controllers;

use \yii\web\Controller;

class AppController extends Controller
{
    const SUBJECTS = [
        '' => '',
        'Математика' => 'Математика',
        'Общество' => 'Общество',
        'Физика' => 'Физика',
        'Литература' => 'Литература',
        'История' => 'История',
        'Биология' => 'Биология',
        'Химия' => 'Химия',
        'Русский язык' => 'Русский язык',
    ];

    const EXAM_TYPES = [
        'ОГЭ' => 'ОГЭ',
        'ЕГЭ' => 'ЕГЭ',
        '10 класс' => '10 класс',
    ];

    const ROLES = [
        'Администратор',
        'Преподаватель',
        'Ученик',
    ];

    const DEFAULT_MONTHS = [
        'Сентябрь',
        'Октябрь',
        'Ноябрь',
        'Декабрь',
        'Январь',
        'Февраль',
        'Март',
        'Апрель',
        'Май'
    ];

    const STREAM_TYPES = [
        'course' => 'course',
        'short' => 'short',
        'long' => 'long',
        'demo' => 'demo',
        'month' => 'month',
        'short_continuation' => 'short_continuation',
        'long_continuation' => 'long_continuation',
    ];

    const STREAM_TYPE_DEMO = self::STREAM_TYPES['demo'];
    const STREAM_TYPE_MONTH = self::STREAM_TYPES['month'];
    const STREAM_TYPE_COURSE = self::STREAM_TYPES['course'];
    const STREAM_TYPE_SHORT = self::STREAM_TYPES['short'];
    const STREAM_TYPE_LONG = self::STREAM_TYPES['long'];
    const STREAM_TYPE_SHORT_CONT = self::STREAM_TYPES['short_continuation'];
    const STREAM_TYPE_LONG_CONT = self::STREAM_TYPES['long_continuation'];

    const STREAM_TYPE_SPEC = 'spec';

    /**
     * Returns representation for any stream type.
     * @param string $type
     * @return string
     */
    public static function getStreamType($type)
    {
        switch ($type)
        {
            case self::STREAM_TYPE_DEMO:
                return 'Демо';
            case self::STREAM_TYPE_MONTH:
                return 'Продление';
            case self::STREAM_TYPE_COURSE:
                return '1 месяц';
            case self::STREAM_TYPE_SHORT:
                return '3х месячный абонемент';
            case self::STREAM_TYPE_LONG:
                return 'Годовой абонемент';
            case self::STREAM_TYPE_SHORT_CONT:
                return 'Продолжение 3х месячного абонемента';
            case self::STREAM_TYPE_LONG_CONT:
                return 'Продолжение годового абонемента';
        }
    }

    public static function isSubscription($type)
    {
        return $type == self::STREAM_TYPE_SHORT
            || $type == self::STREAM_TYPE_LONG
            || $type == self::STREAM_TYPE_SHORT_CONT
            || $type == self::STREAM_TYPE_LONG_CONT;
    }

    public static function castSubType($type)
    {
        if(self::isSubscription($type))
        {
            switch ($type)
            {
                case self::STREAM_TYPE_SHORT:
                    return self::STREAM_TYPE_SHORT_CONT;
                case self::STREAM_TYPE_LONG:
                    return self::STREAM_TYPE_LONG_CONT;
                default:
                    return $type;
            }
        }
        return null;
    }

    const GIFT_TYPES = [
        'stream' => 'stream',
        'extension' => 'extension',
        'short' => 'short',
        'long' => 'long',
    ];

    const GIFT_TYPE_STREAM = self::GIFT_TYPES['stream'];
    const GIFT_TYPE_EXTENSION = self::GIFT_TYPES['extension'];
    const GIFT_TYPE_SHORT = self::GIFT_TYPES['short'];
    const GIFT_TYPE_LONG = self::GIFT_TYPES['long'];
}