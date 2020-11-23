<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "gift_months".
 *
 * @property int $id
 * @property int|null $monthId
 * @property int|null $giftId
 *
 * @property Months $month
 * @property Months $gift
 */
class GiftMonths extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gift_months';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['monthId', 'giftId'], 'default', 'value' => null],
            [['monthId', 'giftId'], 'integer'],
            [['monthId'], 'exist', 'skipOnError' => true, 'targetClass' => Months::className(), 'targetAttribute' => ['monthId' => 'id']],
            [['giftId'], 'exist', 'skipOnError' => true, 'targetClass' => Months::className(), 'targetAttribute' => ['giftId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'monthId' => Yii::t('app', 'Month ID'),
            'giftId' => Yii::t('app', 'Gift ID'),
        ];
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
     * Gets query for [[Gift]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGift()
    {
        return $this->hasOne(Months::className(), ['id' => 'giftId']);
    }
}