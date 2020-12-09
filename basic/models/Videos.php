<?php

namespace app\models;

use Yii;
use yii\validators\UrlValidator;

/**
 * This is the model class for table "videos".
 *
 * @property int $id
 * @property int|null $lessonId
 * @property string|null $url
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
            [['url', 'lessonId'], 'required'],
            [['lessonId'], 'integer'],
            [['url'], 'url', 'validSchemes' => ['https', 'http']],
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
            'url' => Yii::t('app', 'Ссылка на видео'),
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
