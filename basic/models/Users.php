<?php

namespace app\models;

use app\controllers\AppController;
use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $login
 * @property string $name
 * @property string $email
 * @property string $vk
 * @property string $description
 * @property int $role
 * @property int $teacherId
 * @property string $authKey
 * @property string $access_token
 * @property string $password
 * @property string $createdAt
 */
class Users extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public $username;

    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['login', 'name', 'role'], 'required'],
            [['password'], 'string', 'length' => [6, 24]],
            [['email', 'vk', 'description', 'teacherId', 'role', 'authKey', 'access_token'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ИД'),
            'login' => Yii::t('app', 'Логин'),
            'name' => Yii::t('app', 'Имя'),
            'email' => Yii::t('app', 'E-mail'),
            'vk' => Yii::t('app', 'Ссылка на ВК'),
            'description' => Yii::t('app', 'Обо мне'),
            'role' => Yii::t('app', 'Роль'),
            'teacherId' => Yii::t('app', 'Ссылка на преподавателя'),
            'authKey' => Yii::t('app', 'Ключ аутентификации'),
            'password' => Yii::t('app', 'Пароль'),
        ];
    }
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['login' => 'vk'.$token['id']]);
    }
    public static function findByUsername($username)
    {
        return static::findOne(['login' => $username]);
    }
    public function getId()
    {
        return $this->id;
    }
    public function getAuthKey()
    {
        return $this->authKey;
    }
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }
    public function beforeSave($insert)
    {
        $createdAt = date_create_from_format('d.m.Y H:i:s', $this->createdAt);
        if(!empty($this->createdAt))
            $this->createdAt = $createdAt->format('Y-m-d H:i:s');

        $this->authKey = Yii::$app->getSecurity()->generateRandomString();

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $this->AssignRole($this->role, $this->id);

        parent::afterSave($insert, $changedAttributes);
    }

    public function afterFind()
    {
        if(!empty($this->createdAt))
            $this->createdAt = date_create_from_format('Y-m-d H:i:s', $this->createdAt)->format('d.m.Y H:i:s');

        parent::afterFind();
    }

    public function isAdmin()
    {
        return $this->role == 0;
    }
    public function isTeacher()
    {
        return $this->role == 1;
    }
    public function isPupil()
    {
        return $this->role == 2;
    }

    public function getTeacher()
    {
        return $this->hasOne(Teachers::class, ['id' => 'teacherId']);
    }
    public function getMonths()
    {
        return $this
            ->hasMany(Months::class, ['id' => 'monthId'])
            ->viaTable('bought_courses', ['userId' => 'id'])
            ->orderBy(['id' => SORT_ASC]);
    }
    public function getStreams()
    {
        return $this
            ->hasMany(UsersStream::class, ['userId' => 'id'])
            ->orderBy(['id' => SORT_ASC]);
    }
    public function getCourses()
    {
        return $this
            ->hasMany(Courses::class, ['id' => 'courseId'])
            ->viaTable('bought_courses', ['userId' => 'id'])
            ->orderBy(['id' => SORT_ASC]);
    }

    public function getCoursesBySubject($subject)
    {
        return $this
            ->hasMany(Courses::class, ['id' => 'courseId'])
            ->viaTable('bought_courses', ['userId' => 'id'])
            ->orderBy(['id' => SORT_ASC])
            ->where(['subject' => $subject]);
    }

    protected function AssignRole($role, $id)
    {
        $auth = Yii::$app->authManager;
        $auth->revokeAll($id);

        switch ($role){
            case 0:
                $auth->assign($auth->getRole('admin'), $id);
                break;
            case 1:
                $auth->assign($auth->getRole('teacher'), $id);
                break;
            default:
                $auth->assign($auth->getRole('user'), $id);
                break;
        }
    }
}
