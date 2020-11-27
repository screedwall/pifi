<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "coupons".
 *
 * @property int $id
 * @property int|null $count
 * @property bool|null $unique
 * @property int|null $monthId
 *
 * @property Months $month
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
            [['count', 'monthId', 'code', 'discount'], 'required'],
            [['count', 'monthId'], 'integer'],
            [['discount'], 'double'],
            [['unique'], 'boolean'],
            [['code'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'count' => Yii::t('app', 'Количество'),
            'discount' => Yii::t('app', 'Скидка'),
            'code' => Yii::t('app', 'Код купона'),
            'rest' => Yii::t('app', 'Остаток'),
            'unique' => Yii::t('app', 'Unique'),
            'monthId' => Yii::t('app', 'Месяц'),
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
}
