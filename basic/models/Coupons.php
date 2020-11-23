<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "coupons".
 *
 * @property int $id
 * @property int|null $courseId
 * @property int|null $count
 * @property bool|null $unique
 *
 * @property Courses $course
 */
class Coupons extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'coupons';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['courseId', 'count'], 'default', 'value' => null],
            [['courseId', 'count'], 'integer'],
            [['unique'], 'boolean'],
            [['courseId'], 'exist', 'skipOnError' => true, 'targetClass' => Courses::className(), 'targetAttribute' => ['courseId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'courseId' => Yii::t('app', 'Course ID'),
            'count' => Yii::t('app', 'Count'),
            'unique' => Yii::t('app', 'Unique'),
        ];
    }

    /**
     * Gets query for [[Course]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCourse()
    {
        return $this->hasOne(Courses::className(), ['id' => 'courseId']);
    }
}
