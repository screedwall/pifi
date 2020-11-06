<?php


namespace app\controllers;

use \yii\web\Controller;

class AppController extends Controller
{
    public static  function getSubjects()
    {
        return array('Матан' => 'Матан', 'Физика' => 'Физика');
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