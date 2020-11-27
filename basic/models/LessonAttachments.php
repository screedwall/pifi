<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lesson_attachments".
 *
 * @property int $id
 * @property string $name
 * @property string $path
 * @property int $lessonId
 *
 * @property Lessons $lesson
 */
class LessonAttachments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lesson_attachments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'path', 'lessonId'], 'required'],
            [['lessonId'], 'default', 'value' => null],
            [['lessonId'], 'integer'],
            [['name', 'path'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'path' => Yii::t('app', 'Path'),
            'lessonId' => Yii::t('app', 'Lesson ID'),
        ];
    }

    /**
     * Gets query for [[Lesson]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLesson()
    {
        return $this->hasOne(Lessons::class, ['id' => 'lessonId']);
    }
}
