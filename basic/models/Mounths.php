<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mounths".
 *
 * @property int $id
 * @property string $name
 * @property string $dateFrom
 * @property string $dateTo
 * @property string $course
 */
class Mounths extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mounths';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'dateFrom', 'dateTo', 'course'], 'required'],
            [['dateFrom', 'dateTo'], 'date', 'format' => 'dd.MM.yyyy'],
            ['dateFrom', 'validateDates'],
            [['name', 'course'], 'string', 'max' => 255],
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
            'name' => Yii::t('app', 'Имя'),
            'dateFrom' => Yii::t('app', 'Дата начала'),
            'dateTo' => Yii::t('app', 'Дата окончания'),
            'course' => Yii::t('app', 'Курс'),
        ];
    }
    public function getCoursee()
    {
        return $this->hasOne(Courses::class, ['id' => 'course']);
    }
    public function getLessons()
    {
        return $this->hasMany(Lessons::class, ['mounth' => 'id']);
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

        $this->course = Courses::findOne(['id' => $this->course])->name;

        parent::afterFind();
    }
}
