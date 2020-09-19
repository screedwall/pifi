<?php

namespace app\controllers\admin;

use yii\helpers\Url;
use Yii;
use app\models\mounths;
use app\models\MounthsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MounthsController implements the CRUD actions for mounths model.
 */
class MounthsController extends Controller
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
     * Lists all mounths models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MounthsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single mounths model.
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
     * Creates a new mounths model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new mounths();
        $courseId = Yii::$app->request->get('courseId');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['admin/courses/update', 'id' => $model->course, '#' => 'mounths']);
        }

        return $this->render('create', [
            'model' => $model,
            'courseId' => $courseId,
        ]);
    }

    /**
     * Updates an existing mounths model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $courseId)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['admin/courses/update', 'id' => $courseId, '#' => 'mounths']);
        }

        return $this->render('update', [
            'model' => $model,
            'courseId' => $courseId
        ]);
    }

    /**
     * Deletes an existing mounths model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $courseId)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['admin/courses/update', 'id' => $courseId, '#' => 'mounths']);
    }

    /**
     * Finds the mounths model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return mounths the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = mounths::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
