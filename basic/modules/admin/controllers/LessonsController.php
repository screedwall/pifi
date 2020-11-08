<?php

namespace app\modules\admin\controllers;

use app\models\lesson_attachments;
use Yii;
use app\models\Lessons;
use app\models\LessonsSearch;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * LessonsController implements the CRUD actions for Lessons model.
 */
class LessonsController extends Controller
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
     * Lists all Lessons models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LessonsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Lessons model.
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
     * Creates a new Lessons model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Lessons();
        $courseId = Yii::$app->request->get('courseId');
        $monthId = Yii::$app->request->get('monthId');
        $model->courseId = $courseId;
        $model->monthId = $monthId;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['months/update', 'id' => $model->monthId, 'courseId' => $model->courseId]);
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    /**
     * Updates an existing Lessons model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $request = Yii::$app->request;
        $courseId = $request->get('courseId');
        $monthId = $request->get('monthId');
        $model->courseId = $courseId;
        $model->monthId = $monthId;

        if ($model->load($request->post()) && $model->save()) {
            return $this->redirect(['months/update', 'id' => $model->monthId, 'courseId' => $model->courseId]);
        }

        return $this->render('update', [
            'model' => $model
        ]);
    }

    /**
     * Deletes an existing Lessons model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Finds the Lessons model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Lessons the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Lessons::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
