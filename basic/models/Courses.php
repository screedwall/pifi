<?php

namespace app\models;

use app\controllers\AppController;
use phpDocumentor\Reflection\File;
use Yii;
use yii\web\UploadedFile;
use yii\debug\models\search\Debug;

/**
 * This is the model class for table "courses".
 *
 * @property string id
 * @property string $name
 * @property string $shortDescription
 * @property boolean $isVisible
 * @property boolean $isSpec
 * @property string $description
 * @property int $teacherId
 * @property string $subject
 * @property string $examType
 * @property string $thumbnail
 */
class Courses extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'courses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'shortDescription', 'description', 'teacherId', 'subject', 'examType'], 'required'],
            [['description', 'thumbnail'], 'string'],
            [['isVisible', 'isSpec'], 'boolean'],
            [['teacherId'], 'integer'],
            [['name', 'subject', 'examType'], 'string', 'max' => 255],
            [['shortDescription'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Название курса'),
            'shortDescription' => Yii::t('app', 'Краткое описание'),
            'isVisible' => Yii::t('app', 'Невидимый курс'),
            'isSpec' => Yii::t('app', 'Это спецкурс'),
            'description' => Yii::t('app', 'Описание'),
            'teacherId' => Yii::t('app', 'Преподаватель'),
            'subject' => Yii::t('app', 'Предмет'),
            'examType' => Yii::t('app', 'ОГЭ/ЕГЭ'),
            'thumbnail' => Yii::t('app', 'Изменить картинку'),
        ];
    }
    public function getTeacher()
    {
        return $this->hasOne(Teachers::class, ['id' => 'teacherId']);
    }
    public function getMonths()
    {
        return $this->hasMany(Months::class, ['courseId' => 'id'])
            ->orderBy(['id' => SORT_ASC])
            ->orderBy(['dateFrom' => SORT_ASC]);
    }

    public function getLessons()
    {
        return $this->hasMany(Lessons::class, ['courseId' => 'id'])
            ->orderBy(['id' => SORT_ASC]);
    }

    public function getUsers()
    {
        return $this
            ->hasMany(Users::class, ['id' => 'userId'])
            ->viaTable('bought_courses', ['courseId' => 'id']);
    }

    public function dateFrom()
    {
        if(count($this->months) > 0)
        {
            $earliest = date_create_from_format('d.m.Y', $this->months[0]->dateTo);
            foreach ($this->months as $month)
            {
                $monthDate = date_create_from_format('d.m.Y', $month->dateFrom);
                if($monthDate < $earliest)
                    $earliest = $monthDate;
            }
            return $earliest->format('d.m.Y');
        }else{
            return null;
        }
    }

    public function currentMonth()
    {
        if(count($this->months) > 0)
        {
            $current = new \DateTime();
            foreach ($this->months as $month)
            {
                $beginDate = date_create_from_format('d.m.Y', $month->dateFrom);
                $endDate = date_create_from_format('d.m.Y', $month->dateTo);
                if($current > $beginDate && $current < $endDate)
                    return $month;
            }
        }

        return null;
    }

    public function dateTo()
    {
        if(count($this->months) > 0)
        {
            $latest = date_create_from_format('d.m.Y', $this->months[0]->dateTo);
            foreach ($this->months as $month)
            {
                $monthDate = date_create_from_format('d.m.Y', $month->dateTo);
                if($monthDate > $latest)
                    $latest = $monthDate;
            }
            return $latest->format('d.m.Y');
        }else{
            return null;
        }
    }

    public function price($type = 'course')
    {
        if(count($this->months) > 0)
        {
            $month  = $this->currentMonth();
            if(!empty($month))
                switch ($type){
                    case 'course':
                        return $month->price;
                    case 'short':
                        return $month->priceShort;
                    case 'long':
                        return $month->priceLong;
                    case 'month':
                        $amount = 0;
                        $stream = UsersStream::findOne(['courseId' => $this->id, 'userId' => Yii::$app->user->identity->getId()]);
                        if(!empty($stream))
                            $amount = $stream->month->price;
                        return $amount;
                    case 'spec':
                        return Months::findOne(['courseId' => $this->id])->price;
                }
        }

        return 0;
    }

    public function specMonth()
    {
        return Months::findOne(['courseId' => $this->id]);
    }
}
