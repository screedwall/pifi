<?php

namespace app\models;

use app\controllers\AppController;
use Yii;
use yii\debug\models\search\Debug;

/**
 * This is the model class for table "courses".
 *
 * @property string id
 * @property string $name
 * @property string $shortDescription
 * @property string $description
 * @property string $dateFrom
 * @property string $dateTo
 * @property int $teacherId
 * @property string $subject
 * @property string $examType
 * @property int $price
 */
class Courses extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'courses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'shortDescription', 'description', 'dateFrom', 'dateTo', 'teacherId', 'subject', 'examType', 'price'], 'required'],
            [['description'], 'string'],
            [['dateFrom', 'dateTo'], 'date', 'format' => 'dd.MM.yyyy'],
            ['dateFrom', 'validateDates'],
            [['teacherId', 'price'], 'integer'],
            [['name', 'subject', 'examType'], 'string', 'max' => 255],
            [['shortDescription'], 'string', 'max' => 300],
        ];
    }

    public function validateDates(){
        $dateFrom = date_create_from_format('d.m.Y', $this->dateFrom);
        $dateTo = date_create_from_format('d.m.Y', $this->dateTo);

        if($dateFrom > $dateTo)
        {
            $this->addError('dateFrom','Дата начала не может быть больше даты окончания');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Название курса'),
            'shortDescription' => Yii::t('app', 'Краткое описание'),
            'description' => Yii::t('app', 'Описание'),
            'dateFrom' => Yii::t('app', 'Дата начала'),
            'dateTo' => Yii::t('app', 'Дата окончания'),
            'teacherId' => Yii::t('app', 'Преподаватель'),
            'subject' => Yii::t('app', 'Предмет'),
            'examType' => Yii::t('app', 'ОГЭ/ЕГЭ'),
            'price' => Yii::t('app', 'Цена'),
        ];
    }
    public function getTeacher()
    {
        return $this->hasOne(Teachers::class, ['id' => 'teacherId']);
    }
    public function getMonths()
    {
        return $this->hasMany(Months::class, ['courseId' => 'id']);
    }
    public function getUsers()
    {
        return $this
            ->hasMany(Users::class, ['id' => 'userId'])
            ->viaTable('bought_courses', ['courseId' => 'id']);
    }
    public function beforeSave($insert)
    {
        $dateFrom = date_create_from_format('d.m.Y', $this->dateFrom);
        $dateTo = date_create_from_format('d.m.Y', $this->dateTo);

        $this->dateFrom = $dateFrom->format('Y-m-d');
        $this->dateTo = $dateTo->format('Y-m-d');

        return parent::beforeSave($insert);
    }
    public function afterFind()
    {
        $this->dateFrom = date_create_from_format('Y-m-d', $this->dateFrom)->format('d.m.Y');
        $this->dateTo = date_create_from_format('Y-m-d', $this->dateTo)->format('d.m.Y');

        parent::afterFind();
    }
}
