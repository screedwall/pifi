<?php

namespace app\modules\admin\controllers;

use app\controllers\AppController;
use app\models\BoughtCourses;
use app\models\Courses;
use app\models\Months;
use Yii;
use app\models\Users;
use app\models\UsersSearch;
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
            $months = $request->post('months');

            $current = BoughtCourses::find()->where(['userId' => $id])->all();
            foreach ($current as $item) {
                $item->delete();
            }
            if(isset($months))
                foreach ($months as $month) {
                    $boughtCourse = new BoughtCourses();
                    $boughtCourse->userId = $id;
                    $boughtCourse->monthId = $month;
                    $boughtCourse->courseId = Months::findOne([$month])->courseId;
                    $boughtCourse->save();
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

    public function actionList($q)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        $users = Users::find()
                        ->select(['id', 'CONCAT(name,\' \',vk) AS name'])
                        ->orFilterWhere(['like', 'UPPER(name)', mb_strtoupper($q)])
                        ->orFilterWhere(['like', 'UPPER(vk)', mb_strtoupper($q)])
                        ->all();
        $out['results'] = array_values($users);

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
