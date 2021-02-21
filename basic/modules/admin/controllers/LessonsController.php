<?php

namespace app\modules\admin\controllers;

use app\models\lesson_attachments;
use app\models\Videos;
use Yii;
use app\models\Lessons;
use app\models\LessonsSearch;
use yii\base\Exception;
use yii\base\Model;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use wbraganca\dynamicform\DynamicFormWidget;
use yii\helpers\ArrayHelper;

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
     * Creates a new Lessons model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Lessons();
        $modelsVideo = [new Videos];
        $courseId = Yii::$app->request->get('courseId');
        $monthId = Yii::$app->request->get('monthId');
        $model->courseId = $courseId;
        $model->monthId = $monthId;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $modelsVideo = \app\models\Model::createMultiple(Videos::class);
            Model::loadMultiple($modelsVideo, Yii::$app->request->post());

            $valid = Model::validateMultiple($modelsVideo);

            $transaction = Yii::$app->db->beginTransaction();

            try {
                $flag = true;
                foreach ($modelsVideo as $modelVideo) {
                    $modelVideo->lessonId = $model->id;
                    if (! ($flag = $modelVideo->save(false))) {
                        $transaction->rollBack();
                        break;
                    }
                }
                if ($flag) {
                    $transaction->commit();
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }

            return $this->redirect(['months/update', 'id' => $model->monthId, 'courseId' => $model->courseId]);
        }

        return $this->render('create', [
            'model' => $model,
            'modelsVideo' => (empty($modelsVideo)) ? [new Videos] : $modelsVideo,
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
        $modelsVideo = $model->videos;
        $request = Yii::$app->request;
        $courseId = $request->get('courseId');
        $monthId = $request->get('monthId');
        $model->courseId = $courseId;
        $model->monthId = $monthId;

        if ($model->load($request->post()) && $model->save()) {
            $oldIDs = ArrayHelper::map($modelsVideo, 'id', 'id');
            $modelsVideo = \app\models\Model::createMultiple(Videos::class);
            Model::loadMultiple($modelsVideo, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsVideo, 'id', 'id')));

            $valid = Model::validateMultiple($modelsVideo);

            $transaction = Yii::$app->db->beginTransaction();

            try {
                if (!empty($deletedIDs))
                    Videos::deleteAll(['id' => $deletedIDs]);
                $flag = true;
                foreach ($modelsVideo as $modelVideo) {
                    $modelVideo->lessonId = $id;
                    if (! ($flag = $modelVideo->save(false))) {
                        $transaction->rollBack();
                        break;
                    }
                }
                if ($flag) {
                    $transaction->commit();
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }

            return $this->redirect(['months/update', 'id' => $model->monthId, 'courseId' => $model->courseId]);
        }

        return $this->render('update', [
            'model' => $model,
            'modelsVideo' => (empty($modelsVideo)) ? [new Videos] : $modelsVideo,
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
