<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "teachers".
 *
 * @property int $id
 * @property string $name
 * @property string $subject
 * @property string $description
 * @property string $contact
 * @property string $thumbnail
 * @property string $splash
 */
class Teachers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'teachers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'subject', 'description', 'contact'], 'required'],
            [['description', 'thumbnail', 'splash'], 'string'],
            [['name', 'subject', 'contact'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Ид'),
            'name' => Yii::t('app', 'Имя'),
            'subject' => Yii::t('app', 'Предмет'),
            'description' => Yii::t('app', 'Описание'),
            'contact' => Yii::t('app', 'Контакт'),
            'splash' => Yii::t('app', 'Картинка'),
            'thumbnail' => Yii::t('app', 'Миниатюра'),
        ];
    }
}
