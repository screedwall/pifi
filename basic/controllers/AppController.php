<?php


namespace app\controllers;

use \yii\web\Controller;

class AppController extends Controller
{
    public static  function getSubjects()
    {
        return [
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
    }
    public static  function getExams()
    {
        return array('ОГЭ' => 'ОГЭ', 'ЕГЭ' => 'ЕГЭ');
    }
    public static function getRoles()
    {
        return array('Администратор', 'Преподаватель', 'Ученик');
    }

}