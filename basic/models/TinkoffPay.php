<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tinkoffpay".
 *
 * @property int $id
 * @property int|null $amount
 * @property string|null $status
 * @property string|null $createdAt
 * @property int|null $courseId
 * @property int|null $monthId
 * @property int|null $userId
 * @property string|null $type
 *
 * @property Courses $course
 * @property Months $month
 * @property Users $user
 */
class Tinkoffpay extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tinkoffpay';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['amount', 'courseId', 'monthId', 'userId'], 'required'],
            [['courseId', 'monthId', 'userId'], 'integer'],
            ['amount', 'double'],
            [['status', 'type'], 'string', 'max' => 255],
            [['courseId'], 'exist', 'skipOnError' => true, 'targetClass' => Courses::className(), 'targetAttribute' => ['courseId' => 'id']],
            [['monthId'], 'exist', 'skipOnError' => true, 'targetClass' => Months::className(), 'targetAttribute' => ['monthId' => 'id']],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['userId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'amount' => Yii::t('app', 'Amount'),
            'status' => Yii::t('app', 'Status'),
            'createdAt' => Yii::t('app', 'Created At'),
            'courseId' => Yii::t('app', 'Course ID'),
            'monthId' => Yii::t('app', 'Month ID'),
            'userId' => Yii::t('app', 'User ID'),
            'type' => Yii::t('app', 'Type'),
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

    /**
     * Gets query for [[Month]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMonth()
    {
        return $this->hasOne(Months::className(), ['id' => 'monthId']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'userId']);
    }
}
