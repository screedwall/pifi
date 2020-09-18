<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lessons".
 *
 * @property int $id
 * @property string $name
 * @property string $shortDescription
 * @property string $description
 * @property string $video
 * @property string $lessonDate
 * @property string $homeworkDate
 * @property int $mounth
 * @property int $course
 */
class Lessons extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lessons';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'shortDescription', 'description', 'video', 'lessonDate', 'homeworkDate', 'mounth', 'course'], 'required'],
            [['description'], 'string'],
            [['lessonDate', 'homeworkDate'], 'date', 'format' => 'dd.MM.yyyy HH:mm:ss'],
            [['mounth', 'course'], 'integer'],
            [['name', 'shortDescription', 'video'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ИД'),
            'name' => Yii::t('app', 'Имя'),
            'shortDescription' => Yii::t('app', 'Краткое описание'),
            'description' => Yii::t('app', 'Описание'),
            'video' => Yii::t('app', 'Видео'),
            'lessonDate' => Yii::t('app', 'Время урока'),
            'homeworkDate' => Yii::t('app', 'Время домашки'),
            'mounth' => Yii::t('app', 'Месяц'),
            'course' => Yii::t('app', 'Курс'),
        ];
    }
    public function beforeSave($insert)
    {
        $lessonDate = date_create_from_format('d.m.Y H:i:s', $this->lessonDate);
        $homeworkDate = date_create_from_format('d.m.Y H:i:s', $this->homeworkDate);

        $this->lessonDate = $lessonDate->format('Y-m-d H:i:s');
        $this->homeworkDate = $homeworkDate->format('Y-m-d H:i:s');

        return parent::beforeSave($insert);
    }
    public function afterFind()
    {
        $this->lessonDate = date_create_from_format('Y-m-d H:i:s', $this->lessonDate)->format('d.m.Y H:i:s');
        $this->homeworkDate = date_create_from_format('Y-m-d H:i:s', $this->homeworkDate)->format('d.m.Y H:i:s');

        $this->mounth = Mounths::findOne(['id' => $this->mounth])->name;
        $this->course = Courses::findOne(['id' => $this->course])->name;

        parent::afterFind();
    }
}
