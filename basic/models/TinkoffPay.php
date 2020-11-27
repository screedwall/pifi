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
class TinkoffPay extends \yii\db\ActiveRecord
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

    public function beforeSave($insert)
    {
        $createdAt = date_create_from_format('d.m.Y H:i:s', $this->createdAt);
        if(!empty($this->createdAt))
            if(!empty($createdAt))
                $this->createdAt = $createdAt->format('Y-m-d H:i:s');

        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        if(!empty($this->createdAt))
            $this->createdAt = date_create_from_format('Y-m-d H:i:s', $this->createdAt)->format('d.m.Y H:i:s');

        parent::afterFind();
    }
}
