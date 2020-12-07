<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "videos".
 *
 * @property int $id
 * @property int|null $lessonId
 * @property string|null $video
 *
 * @property Lessons $lesson
 */
class Videos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'videos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lessonId'], 'default', 'value' => null],
            [['lessonId'], 'integer'],
            [['video'], 'string', 'max' => 255],
            [['lessonId'], 'exist', 'skipOnError' => true, 'targetClass' => Lessons::className(), 'targetAttribute' => ['lessonId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'lessonId' => Yii::t('app', 'Lesson ID'),
            'video' => Yii::t('app', 'Видео'),
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
