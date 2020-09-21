<?php


namespace app\controllers;

use \yii\web\Controller;

class AppController extends Controller
{
    public function getSubjects()
    {
        return array('Матан' => 'Матан', 'Физика' => 'Физика');
    }
    public function getExams()
    {
        return array('ОГЭ' => 'ОГЭ', 'ЕГЭ' => 'ЕГЭ');
    }
    public function getRoles()
    {
        return array('Ученик' => 'Ученик', 'Преподаватель' => 'Преподаватель', 'Администратор' => 'Администратор');
    }

}