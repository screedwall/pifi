<?php

namespace app\modules\admin\controllers;

use app\controllers\AppController;
use app\models\BoughtCourses;
use app\models\GiftMonths;
use app\models\Months;
use Cassandra\Date;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Courses;
use app\models\CoursesSearch;
use yii\web\UploadedFile;

/**
 * CoursesController implements the CRUD actions for Courses model.
 */
class CoursesController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'uploadPhoto' => [
                'class' => 'budyaga\cropper\actions\UploadAction',
                'url' => '/uploads/courses',
                'path' => 'uploads/courses',
            ]
        ];
    }

    /**
     * Lists all Courses models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CoursesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Courses model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Courses();

        if ($model->load(Yii::$app->request->post())&&$model->save()) {
            if(!$model->isSpec)
                foreach (AppController::DEFAULT_MONTHS as $defaultMonth)
                {
                    $month = new Months();
                    $month->name = $defaultMonth;
                    $month->courseId = $model->id;
                    $month->dateFrom = date('d.m.Y');
                    $month->dateTo = date('d.m.Y');
                    $month->save();
                }
            else
            {
                $month = new Months();
                $month->name = "Месяц спецкурса";
                $month->courseId = $model->id;
                $month->dateFrom = date('d.m.Y');
                $month->dateTo = date('d.m.Y');
                $month->save();
            }

            return $this->redirect(['update', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Courses model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())&&$model->save()) {
                return $this->redirect('index');
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDownload($id)
    {
        $model = $this->findModel($id);
        $path = Yii::getAlias('@webroot')."/"."uploads/course_users/".$id.".txt";
        $file = fopen($path, 'w');

        $users = BoughtCourses::find()
            ->where(['courseId' => $id])
            ->select(['userId'])
            ->distinct()
            ->asArray()
            ->all();

        foreach ($users as $user)
            fputcsv($file, $user);

        fclose($file);

        return Yii::$app->response->sendFile($path, 'Выгрузка '.$model->name.".txt");
    }

    public function actionCopy($id)
    {
        $originModel = $this->findModel($id);
        $data = $originModel->attributes;
        while(Courses::find()->where(['name' => $data['name']])->count() > 0){
            $data['name'] = $data['name'].' копия';
        }

        $model = new Courses();
        $model->setAttributes($data);
        if ($model->save()) {
            $months = $originModel->months;
            if(count($months) > 0)
                foreach ($months as $month) {
                    $copyMonth = new Months();
                    $copyMonth->setAttributes($month->attributes);
                    $copyMonth->courseId = $model->id;
                    $copyMonth->save();
                }

            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Courses model.
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

    /**
     * Finds the Courses model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Courses the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Courses::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
