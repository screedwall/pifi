<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bought_courses".
 *
 * @property int $id
 * @property int $userId
 * @property int $courseId
 * @property int $monthId
 */
class BoughtCourses extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bought_courses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userId', 'courseId', 'monthId'], 'required'],
            [['userId', 'courseId', 'monthId'], 'default', 'value' => null],
            [['userId', 'courseId', 'monthId'], 'integer'],
            [['courseId'], 'exist', 'skipOnError' => true, 'targetClass' => Courses::class, 'targetAttribute' => ['courseId' => 'id']],
            [['monthId'], 'exist', 'skipOnError' => true, 'targetClass' => Months::class, 'targetAttribute' => ['monthId' => 'id']],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => Users::class, 'targetAttribute' => ['userId' => 'id']],
        ];
    }

    /**
     * Gets query for [[Course]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Courses::class, ['id' => 'courseId']);
    }

    /**
     * Gets query for [[Month]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMonth()
    {
        return $this->hasOne(Months::class, ['id' => 'monthId']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::class, ['id' => 'userId']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'userId' => Yii::t('app', 'User ID'),
            'courseId' => Yii::t('app', 'Course ID'),
            'monthId' => Yii::t('app', 'Month ID'),
        ];
    }
}
