<?php

namespace app\models;

use app\controllers\AppController;
use Yii;

/**
 * This is the model class for table "gift_months".
 *
 * @property int $id
 * @property int|null $monthId
 * @property int|null $giftId
 * @property boolean|null $isExtension
 * @property boolean|null $isShort
 * @property boolean|null $isLong
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

    public static function getGiftsByType($monthId, $type)
    {
        $gifts = self::find()
            ->with('gift')
            ->where(['monthId' => $monthId]);

        switch ($type)
        {
            case AppController::STREAM_TYPE_COURSE:
            case AppController::STREAM_TYPE_EXTRA_SHORT:
                $gifts->andWhere(['isExtension' => false, 'isShort' => false, 'isLong' => false]);
                break;
            case AppController::STREAM_TYPE_MONTH:
                $gifts->andWhere(['isExtension' => true]);
                break;
            case AppController::STREAM_TYPE_SHORT:
                $gifts->andWhere(['isShort' => true]);
                break;
            case AppController::STREAM_TYPE_LONG:
                $gifts->andWhere(['isLong' => true]);
                break;
            default:
                $gifts->andWhere('0!=0');
                break;
        }
        return $gifts->all();
    }
}
