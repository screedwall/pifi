<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lessons".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $video
 * @property string $lessonDate
 * @property string $homeworkDate
 * @property int $monthId
 * @property int $courseId
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
            [['name', 'lessonDate', 'homeworkDate', 'monthId', 'courseId'], 'required'],
            [['description'], 'string'],
            [['lessonDate', 'homeworkDate'], 'date', 'format' => 'dd.MM.yyyy HH:mm'],
            [['monthId', 'courseId'], 'integer'],
            [['name', 'video'], 'string', 'max' => 255],
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
            'description' => Yii::t('app', 'Описание'),
            'video' => Yii::t('app', 'Видео'),
            'lessonDate' => Yii::t('app', 'Время урока'),
            'homeworkDate' => Yii::t('app', 'Время домашки'),
            'monthId' => Yii::t('app', 'Месяц'),
            'courseId' => Yii::t('app', 'Курс'),
        ];
    }
    public function getMonth()
    {
        return $this->hasOne(Months::class, ['id' => 'monthId']);
    }
    public function getCourse()
    {
        return $this->hasOne(Courses::class, ['id' => 'courseId']);
    }

    public function getAttachments()
    {
        return $this->hasMany(LessonAttachments::class, ['lessonId' => 'id'])
            ->orderBy(['id' => SORT_ASC]);
    }

    public function beforeSave($insert)
    {
        $lessonDate = date_create_from_format('d.m.Y H:i', $this->lessonDate);
        $homeworkDate = date_create_from_format('d.m.Y H:i', $this->homeworkDate);

        $this->lessonDate = $lessonDate->format('Y-m-d H:i');
        $this->homeworkDate = $homeworkDate->format('Y-m-d H:i');

        return parent::beforeSave($insert);
    }
    public function afterFind()
    {
        $this->lessonDate = date_create_from_format('Y-m-d H:i:s', $this->lessonDate)->format('d.m.Y H:i');
        $this->homeworkDate = date_create_from_format('Y-m-d H:i:s', $this->homeworkDate)->format('d.m.Y H:i');

        parent::afterFind();
    }
}
