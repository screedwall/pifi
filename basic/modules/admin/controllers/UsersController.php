<?php

namespace app\modules\admin\controllers;

use app\controllers\AppController;
use app\controllers\PayController;
use app\models\BoughtCourses;
use app\models\Courses;
use app\models\Months;
use app\models\TinkoffPaySearch;
use app\models\UserBCSearch;
use app\models\UsersStream;
use Yii;
use app\models\Users;
use app\models\UsersSearch;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UsersController implements the CRUD actions for Users model.
 */
class UsersController extends AppController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
//                    'change-password' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Users models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Users model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Users();
        $model->createdAt = date('d.m.Y H:i:s');
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    /**
     * Updates an existing Users model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {

        $model = $this->findModel($id);
        $request = Yii::$app->request;
        if ($model->load($request->post()) && $model->save(false)) {
            return $this->redirect(['index']);
        }

        $searchModel = new UserBCSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('update', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionChangePassword($id, $password)
    {
        $model = $this->findModel($id);
        $model->password = Yii::$app->getSecurity()->generatePasswordHash($password);
        if($model->save(false))
            return $this->redirect(['index']);
    }

    /**
     * Deletes an existing Users model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionList($q = null, $id = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'name' => '']];
        if (!is_null($q)) {
            $query  = new Query();
            $query->select(['id', "CONCAT(name,' ',vk) AS text"])
                ->from('users')
                ->orFilterWhere(['like', 'UPPER(name)', mb_strtoupper($q)])
                ->orFilterWhere(['like', 'UPPER(vk)', mb_strtoupper($q)])
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'name' => Users::findOne(['id' => $id])->name];
        }

        return $out;
    }

    public function actionCheckUserStream($userId) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (isset($_POST['depdrop_all_params'])) {
            $monthId = ArrayHelper::getValue(Yii::$app->request->post(), 'depdrop_all_params.month');
            $boughtCourse = BoughtCourses::find()
                ->where(['userId' => $userId, 'monthId' => $monthId])
                ->one();

            $out = [];
            $selected = '';

            if (!empty($boughtCourse))
            {
                $out = [[
                    'id' => 'forbidden',
                    'name' => 'Уже в этом месяце'
                ]];
                return ['output' => $out, 'selected' => $out];
            }
            else
            {
                $month = Months::findOne($monthId);
                if (!$month->course->isSpec) {
                    $stream = UsersStream::findOne(['userId' => $userId, 'courseId' => $month->courseId]);
                    if (empty($stream)) {
                        foreach (AppController::STREAM_TYPES as $STREAM_TYPE)
                            array_push($out, [
                                'id' => $STREAM_TYPE,
                                'name' => AppController::getStreamType($STREAM_TYPE)
                            ]);

                        $toDelete = AppController::STREAM_CONTINUATIONS;
                        foreach ($toDelete as $el) {
                            ArrayHelper::removeValue($out, [
                                'id' => $el,
                                'name' => AppController::getStreamType($el),
                            ]);
                        }
                    } else {
                        array_push($out, [
                            'id' => AppController::STREAM_TYPE_MONTH,
                            'name' => AppController::getStreamType(AppController::STREAM_TYPE_MONTH)
                        ]);
                        array_push($out, [
                            'id' => AppController::STREAM_TYPE_DEMO_MONTH,
                            'name' => AppController::getStreamType(AppController::STREAM_TYPE_DEMO_MONTH)
                        ]);

                        $selected = [];
                    }

                    return ['output' => $out, 'selected' => $selected];
                }
                else
                {
                    $out = [[
                        'id' => AppController::STREAM_TYPE_SPEC,
                        'name' => 'Спецкурс'
                    ]];
                    return ['output' => $out, 'selected' => $out];
                }
            }
        }
        return ['output' => '', 'selected' => ''];
    }

    public function actionCreateMonthUser($userId)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;
        $monthId = $request->post('month');
        $type = $request->post('subscriptionType');
        $courseId = Months::findOne($monthId)->courseId;

        return ['success' => PayController::CreateMonthUser($courseId, $monthId, $userId, $type)];
    }

    /**
     * Finds the Users model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Users the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Users::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
