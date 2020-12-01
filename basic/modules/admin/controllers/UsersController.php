<?php

namespace app\modules\admin\controllers;

use app\controllers\AppController;
use app\models\BoughtCourses;
use app\models\Courses;
use app\models\Months;
use app\models\UsersStream;
use Yii;
use app\models\Users;
use app\models\UsersSearch;
use yii\db\Query;
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
     * Displays a single Users model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
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

            BoughtCourses::deleteAll(['userId' => $id]);

            $getMonths = $request->post('months');
            if(!empty($getMonths))
            {
                $months = Months::find()->where(['in', 'id', $getMonths])->all();
                foreach ($months as $month) {
                    $boughtCourse = new BoughtCourses();
                    $boughtCourse->userId = $id;
                    $boughtCourse->monthId = $month->id;
                    $boughtCourse->courseId = $month->courseId;
                    $boughtCourse->save();
                }
            }

            UsersStream::deleteAll(['userId' => $id]);

            $getStreams = $request->post('streams');
            if(!empty($getStreams))
            {
                $months = Months::find()->where(['in', 'id', $getStreams])->with('gifts')->all();
                foreach ($months as $month) {
                    $stream = new UsersStream();
                    $stream->userId = $id;
                    $stream->courseId = $month->courseId;
                    $stream->monthId = $month->id;
                    $stream->type = 'course';
                    $stream->remains = 0;
                    $stream->save();

                    foreach ($month->gifts as $gift)
                    {
                        $skip = false;
                        foreach ($gift->months as $uMonth)
                            if($uMonth->id == $month->id)
                            {
                                $skip = true;
                                break;
                            }
                        if($skip)
                            continue;

                        $boughtCourse = new BoughtCourses();
                        $boughtCourse->userId = $id;
                        $boughtCourse->courseId = $gift->courseId;
                        $boughtCourse->monthId = $gift->id;
                        $boughtCourse->save();
                    }
                }
            }


            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model
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
