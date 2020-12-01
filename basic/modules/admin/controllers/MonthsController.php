<?php

namespace app\modules\admin\controllers;

use app\models\BoughtCourses;
use app\models\GiftMonths;
use app\models\User;
use app\models\Users;
use app\models\UsersStream;
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

    /*  1) Give month to stream users
     *
     */
    public function actionCreate()
    {
        $model = new Months();
        $courseId = Yii::$app->request->get('courseId');
        $model->courseId = $courseId;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $streamUsers = UsersStream::find()
                ->with('user')
                ->where(['courseId' => $courseId])
                ->andWhere(['>', 'remains', 0])
                ->all();
            foreach ($streamUsers as $streamUser)
            {
                $user = $streamUser->user;
                $skip = false;
                foreach ($user->months as $uMonth)
                    if($uMonth->id == $model->id)
                    {
                        $skip = true;
                        break;
                    }
                if($skip)
                    continue;

                $streamUser->remains--;
                $streamUser->save();

                $boughtCourse = new BoughtCourses();
                $boughtCourse->userId = $user->id;
                $boughtCourse->monthId = $model->id;
                $boughtCourse->courseId = $model->courseId;
                $boughtCourse->isStream = true;
                $boughtCourse->save();
            }

            $gifts = Yii::$app->request->post('gifts');

            if(!empty($gifts))
                foreach ($gifts as $gift) {
                    $giftCourse = new GiftMonths();
                    $giftCourse->monthId = $model->id;
                    $giftCourse->giftId = $gift;
                    $giftCourse->save();
                }

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

    /*  1)  Delete gifts from stream users
     *  2)  Update gifts
     *  3)  Add users with streams
     */
    public function actionUpdate($id, $courseId)
    {
        $model = $this->findModel($id);
        $request = Yii::$app->request;
        if ($model->load($request->post()) && $model->save()) {
            //Adding gift months
            $giftsId = Yii::$app->request->post('gifts');

            $giftMonths = GiftMonths::findAll(['monthId' => $id]);
            $streamUsers = UsersStream::findAll(['monthId' => $id]);
            $giftMonthsIds = [];
            $giftUsersIds = [];
            foreach ($giftMonths as $gift)
            {
                array_push($giftMonthsIds, $gift->giftId);
            }
            foreach ($streamUsers as $streamUser)
            {
                array_push($giftUsersIds, $streamUser->userId);
            }

            if(!empty($giftUsersIds))
            {
                UsersStream::deleteAll(['monthId' => $id]);
                BoughtCourses::deleteAll(['and', ['in','monthId', $giftMonthsIds], ['in', 'userId', $giftUsersIds]]);
            }
            GiftMonths::deleteAll(['monthId' => $id]);

            $gifts = [];
            if(isset($giftsId))
            {
                foreach ($giftsId as $gift) {
                    $giftCourse = new GiftMonths();
                    $giftCourse->monthId = $model->id;
                    $giftCourse->giftId = $gift;
                    $giftCourse->save();
                    array_push($gifts, $giftCourse);
                }
            }

            //Give months to users and register streams
            $usersId = $request->post('users');

            BoughtCourses::deleteAll(['monthId' => $id]);
            if(!empty($usersId))
            {
                $users = Users::find()
                                ->where(['in','id', $usersId])
                                ->with('months')
                                ->with(['streams' => function($query) use ($model) {
                                    return $query->where(['courseId' => $model->courseId]);
                                }])
                                ->all();

                foreach ($users as $user) {
                    if(count($user->streams) == 0 && !$model->course->isSpec) //Register stream and give gifts
                    {
                        $stream = new UsersStream();
                        $stream->userId = $user->id;
                        $stream->courseId = $model->courseId;
                        $stream->monthId = $id;
                        $stream->type = 'course';
                        $stream->remains = 0;
                        $stream->save();

                        foreach ($gifts as $gift)
                        {
                            $skip = false;
                            foreach ($user->months as $uMonth)
                                if($uMonth->id == $model->id)
                                {
                                    $skip = true;
                                    break;
                                }
                            if($skip)
                                continue;

                            $boughtCourse = new BoughtCourses();
                            $boughtCourse->userId = $user->id;
                            $boughtCourse->courseId = $model->courseId;
                            $boughtCourse->monthId = $gift->giftId;
                            $boughtCourse->save();
                        }
                    }

                    $boughtCourse = new BoughtCourses();
                    $boughtCourse->userId = $user->id;
                    $boughtCourse->monthId = $id;
                    $boughtCourse->courseId = $model->courseId;
                    $boughtCourse->save();
                }
            }



            return $this->redirect(['courses/update', 'id' => $courseId, '#' => 'months']);
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
        $model = $this->findModel($id);

        //Returning stream counter
        $boughtCourses = BoughtCourses::find()
            ->where(['monthId' => $id])
            ->andWhere(['isStream' => true])
            ->with('streams')
            ->all();

        foreach ($boughtCourses as $boughtCourse)
        {
            foreach ($boughtCourse->streams as $stream)
            {
                $stream->remains++;
                $stream->save();
            }
        }

        $model->delete();

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
