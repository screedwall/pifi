<?php

namespace app\modules\admin\controllers;

use app\models\BoughtCourses;
use app\models\Users;
use yii\helpers\Url;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Months;
use app\models\MonthsSearch;

/**
 * MonthsController implements the CRUD actions for months model.
 */
class MonthsController extends Controller
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
                ],
            ],
        ];
    }

    /**
     * Lists all months models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MonthsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single months model.
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
     * Creates a new months model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Months();
        $courseId = Yii::$app->request->get('courseId');
        $model->courseId = $courseId;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['courses/update', 'id' => $model->courseId, '#' => 'months']);
        }

        return $this->render('create', [
            'model' => $model,
            'courseId' => $courseId,
        ]);
    }

    /**
     * Updates an existing months model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $courseId)
    {
        $model = $this->findModel($id);
        $request = Yii::$app->request;
        if ($model->load($request->post()) && $model->save()) {
            $users = $request->post('users');

            $current = BoughtCourses::find()->where(['monthId' => $id])->all();
            foreach ($current as $item) {
                $item->delete();
            }
            if(isset($users))
                foreach ($users as $user) {
                    $boughtCourse = new BoughtCourses();
                    $boughtCourse->userId = $user;
                    $boughtCourse->monthId = $id;
                    $boughtCourse->courseId = $model->courseId;
                    $boughtCourse->save();
                }


            return $this->redirect(['courses/update', 'id' => $courseId, '#' => 'months', 'users' => $users]);
        }

        return $this->render('update', [
            'model' => $model,
            'courseId' => $courseId
        ]);
    }

    /**
     * Deletes an existing months model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $courseId)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['courses/update', 'id' => $courseId, '#' => 'months']);
    }

    /**
     * Finds the months model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Months the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Months::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
