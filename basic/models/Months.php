<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "months".
 *
 * @property int $id
 * @property string $name
 * @property string $dateFrom
 * @property string $dateTo
 * @property string $courseId
 */
class Months extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'months';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'dateFrom', 'dateTo', 'courseId'], 'required'],
            [['dateFrom', 'dateTo'], 'date', 'format' => 'dd.MM.yyyy'],
            ['dateFrom', 'validateDates'],
            ['courseId', 'integer'],
            [['price', 'priceShort', 'priceLong'], 'double'],
            [['name'], 'string', 'max' => 255],
        ];
    }
    public function validateDates(){
        $dateFrom = date_create_from_format('d.m.Y', $this->dateFrom);
        $dateTo = date_create_from_format('d.m.Y', $this->dateTo);

        if($dateFrom > $dateTo)
        {
            $this->addError('dateFrom','Дата начала не может быть больше даты окончания');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('app', 'Имя'),
            'price' => Yii::t('app', 'Цена'),
            'priceShort' => Yii::t('app', 'Цена абонемента на 3 месяца'),
            'priceLong' => Yii::t('app', 'Цена годового абонемента'),
            'dateFrom' => Yii::t('app', 'Дата начала'),
            'dateTo' => Yii::t('app', 'Дата окончания'),
            'courseId' => Yii::t('app', 'Курс'),
        ];
    }
    public function getCourse()
    {
        return $this->hasOne(Courses::class, ['id' => 'courseId']);
    }
    public function getLessons()
    {
        return $this->hasMany(Lessons::class, ['monthId' => 'id'])
            ->orderBy(['id' => SORT_ASC]);
    }
    public function getUsers()
    {
        return $this
            ->hasMany(Users::class, ['id' => 'userId'])
            ->viaTable('bought_courses', ['monthId' => 'id']);
    }
    public function getGifts()
    {
        return $this->hasMany(GiftMonths::class, ['monthId' => 'id'])
            ->orderBy(['id' => SORT_ASC]);
    }
    public function getCoupons()
    {
        return $this->hasMany(Coupons::class, ['monthId' => 'id'])
            ->orderBy(['id' => SORT_ASC]);
    }
    public function beforeSave($insert)
    {
        $dateFrom = date_create_from_format('d.m.Y', $this->dateFrom);
        $dateTo = date_create_from_format('d.m.Y', $this->dateTo);

        $this->dateFrom = $dateFrom->format('Y-m-d');
        $this->dateTo = $dateTo->format('Y-m-d');

        return parent::beforeSave($insert);
    }
    public function afterFind()
    {
        if(isset($this->dateFrom))
            $this->dateFrom = date_create_from_format('Y-m-d', $this->dateFrom)->format('d.m.Y');
        if(isset($this->dateTo))
            $this->dateTo = date_create_from_format('Y-m-d', $this->dateTo)->format('d.m.Y');

        parent::afterFind();
    }
}
