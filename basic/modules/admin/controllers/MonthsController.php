<?php

namespace app\modules\admin\controllers;

use app\controllers\AppController;
use app\controllers\PayController;
use app\models\BoughtCourses;
use app\models\Courses;
use app\models\CoursesSearch;
use app\models\GiftMonths;
use app\models\User;
use app\models\Users;
use app\models\UsersStream;
use yii\bootstrap\ActiveForm;
use yii\debug\panels\EventPanel;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Months;
use app\models\MonthsSearch;
use app\models\MonthUsersSearch;
use yii\web\Response;

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
     * Creates a new months model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    /*  1) Give month to stream users
     *
     */
    public function actionCreate($courseId)
    {
        $model = new Months();
        $model->courseId = $courseId;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['courses/update', 'id' => $model->courseId, '#' => 'months']);
        }

        return $this->render('create', [
            'model' => $model,
            'courseId' => $courseId,
        ]);
    }

    function batchGifts($monthId, $giftIds, $giftType)
    {
        if(!empty($giftIds))
        {
            $rows = [];
            foreach ($giftIds as $giftId) {
                array_push($rows, [
                    'monthId' => $monthId,
                    'giftId' => $giftId,
                    'isExtension' => $giftType == AppController::GIFT_TYPE_EXTENSION,
                    'isShort' => $giftType == AppController::GIFT_TYPE_SHORT,
                    'isLong' => $giftType == AppController::GIFT_TYPE_LONG,
                ]);
            }

            $cols = (new GiftMonths)->attributes();
            ArrayHelper::removeValue($cols, 'id');

            Yii::$app->db->createCommand()->batchInsert(GiftMonths::tableName(), $cols, $rows)->execute();
        }
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
     */
    public function actionUpdate($id, $courseId)
    {
        $model = Months::find()
            ->where(['id' => $id])
            ->with('course')
            ->one();
        $searchModel = new MonthUsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $request = Yii::$app->request;
        if ($model->load($request->post()) && $model->save()) {

            //++Save user info before purge
            $monthUsers = [];
            $bcUsers = BoughtCourses::find() //Select all month users
                ->where(['monthId' => $id])
                ->with('stream')
                ->all();

            foreach ($bcUsers as $bcUser)
                array_push($monthUsers, [
                    'userId' => $bcUser->userId,
                    'paymentId' => $bcUser->paymentId,
                    'streamId' => $bcUser->streamId,
                    'type' => ($bcUser->stream->monthId == $id ? $bcUser->stream->type : AppController::STREAM_TYPE_MONTH),
                ]);
            //--Save user info before purge

            //++PURGE Previous linked records
            $giftMonths = GiftMonths::findAll(['monthId' => $id]);//Select all month gifts
            if(!empty($bcUsers))
            {
                $months = ArrayHelper::getColumn($giftMonths, 'giftId');
                array_push($months, intval($id));

                //delete users from month
//                BoughtCourses::deleteAll(['and', ['in','monthId', $months], ['in', 'userId', ArrayHelper::getColumn($bcUsers, 'userId')]]);
            }
            GiftMonths::deleteAll(['monthId' => $id]);
            //--PURGE Previous linked records

            //++Collect different gift months and register
            $gifts = [
                AppController::GIFT_TYPE_STREAM => Yii::$app->request->post('gifts'),
                AppController::GIFT_TYPE_EXTENSION => Yii::$app->request->post('extensions'),
                AppController::GIFT_TYPE_SHORT => Yii::$app->request->post('shorts'),
                AppController::GIFT_TYPE_LONG => Yii::$app->request->post('longs'),
            ];

            foreach ($gifts as $key => $value)
                $this->batchGifts($model->id, $value, $key);
            //--Collect different gift months and register

//            foreach ($monthUsers as $monthUser)
//            {
//                PayController::CreateMonthUser($courseId, $id, $monthUser['userId'], $monthUser['type'], $monthUser['paymentId'], true);
//            }

            return $this->redirect(['courses/update', 'id' => $courseId, '#' => 'months']);
        }

        return $this->render('update', [
            'model' => $model,
            'courseId' => $courseId,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
            ->andWhere(['not', ['streamId' => null]])
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

    public function actionCreateMonthUser($courseId, $monthId)
    {
        $monthId = intval($monthId);
        $courseId = intval($courseId);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $request = Yii::$app->request;
        $userId = $request->post('user');
        $type = $request->post('subscriptionType');

        return ['success' => PayController::CreateMonthUser($courseId, $monthId, $userId, $type)];
    }

    public function actionCheckUserStream($courseId, $monthId) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (isset($_POST['depdrop_parents'])) {
            $userId = end($_POST['depdrop_parents']);
            $currentMonth = Months::find()
                ->where(['id' => $monthId])
                ->with('course')
                ->with(['users' => function ($q) use ($userId) {
                    return $q->where(['id' => $userId]);
                }])
                ->one();

            $out = [];
            $selected = '';

            if(empty($currentMonth->users))
            {
                if (!$currentMonth->course->isSpec) {
                    $stream = UsersStream::findOne(['userId' => $userId, 'courseId' => $courseId]);
                    if (empty($stream)) {
                        foreach (AppController::STREAM_TYPES as $STREAM_TYPE)
                            array_push($out, [
                                'id' => $STREAM_TYPE,
                                'name' => AppController::getStreamType($STREAM_TYPE)
                            ]);

                        $toDelete = [AppController::STREAM_TYPE_MONTH, AppController::STREAM_TYPE_DEMO_MONTH, AppController::STREAM_TYPE_SHORT_CONT, AppController::STREAM_TYPE_LONG_CONT];
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

                    // Shows how you can preselect a value
                    return ['output' => $out, 'selected' => $selected];
                } else {
                    $out = [[
                        'id' => AppController::STREAM_TYPE_SPEC,
                        'name' => 'Спецкурс'
                    ]];
                    return ['output' => $out, 'selected' => $out];
                }
            }
            else
            {
                $out = [[
                    'id' => 'forbidden',
                    'name' => 'Уже в этом месяце'
                ]];
                return ['output' => $out, 'selected' => $out];
            }
        }
        return ['output' => '', 'selected' => ''];
    }

    public function actionUpdateMonthUser($userId, $courseId, $monthId)
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            //Operates only with new users in course without continuations
            $type = $request->post('subscriptionType');
            $isDemoContinued = boolval($request->post('isDemoContinued'));

            $stream = UsersStream::findOne(['userId' => $userId, 'monthId' => $monthId]);

            //TODO : check SPEC
            if((AppController::isSubscription($type) || $type == AppController::STREAM_TYPE_COURSE || $type == AppController::STREAM_TYPE_DEMO) && $stream->type != $type) //Only if type changed
            {
                //Remove linked months & gifts
                $stream->delete();

                $ret = ['success' => PayController::CreateMonthUser($courseId, $monthId, $userId, $type)];
                if($type == AppController::STREAM_TYPE_DEMO)
                {
                    $boughtCourse = BoughtCourses::findOne(['userId' => $userId, 'monthId' => $monthId]);
                    $boughtCourse->isDemo = true;
                    $boughtCourse->isDemoContinued = $isDemoContinued;
                    $boughtCourse->save();
                }
            }
            elseif(AppController::isContinuation($type))
            {
                $boughtCourse = BoughtCourses::findOne(['userId' => $userId, 'monthId' => $monthId]);

                if($type == AppController::STREAM_TYPE_MONTH)
                {
                    $boughtCourse->isDemo = false;
                    $boughtCourse->isDemoContinued = false;
                }
                else
                {
                    $boughtCourse->isDemo = true;
                    $boughtCourse->isDemoContinued = $isDemoContinued;
                }

                $boughtCourse->save();

                $ret = ['success' => true];
            }
            else
                $ret = ['success' => true];

            return $ret;
        }

        $currentMonth = Months::find()
            ->where(['id' => $monthId])
            ->with('course')
            ->one();

        $user = Users::find()
            ->where(['id' => $userId])
            ->with(['streams' => function($query) use ($courseId){
                return $query
                    ->with('month')
                    ->where(['courseId' => $courseId]);
            }])
            ->with(['boughtCourses' => function($q) use ($courseId){
                return $q->where(['bought_courses.courseId' => $courseId])
                    ->with('month');
            }])
            ->one();

        foreach ($user->boughtCourses as $boughtCourse) {
            if($boughtCourse->monthId == $monthId)
                $currentBC = $boughtCourse;
        }

        $isGifted = $currentBC->giftedByMonthId != null;
        if($isGifted)
            $giftParent = Months::find()
                ->where(['id' => $currentBC->giftedByMonthId])
                ->with('course')
                ->one();
        else
            $giftParent = null;

        if($currentBC->streamId != null)
            $isSubscription = true;
        else
            $isSubscription = false;

        if(!$currentMonth->course->isSpec)
        {
            $streamModel = $user->streams[0];
            if($streamModel->monthId != $monthId)
            {
                $firstMonth = $streamModel->month;
                $isNew = false;
            }
            else
            {
                $firstMonth = null;
                $isNew = true;
            }

            $type = $streamModel->type;

            if(!$isNew && AppController::isSubscription($streamModel->type))
                $stream = AppController::castSubType($streamModel->type);
            else
                $stream = $streamModel->type;

            $streamMonths = [];
            foreach ($user->boughtCourses as $boughtCourse) {
                if($boughtCourse->streamId != null)
                    array_push($streamMonths, $boughtCourse);
            }

            $allCourseMonths = [];
            foreach ($user->boughtCourses as $boughtCourse) {
                if($boughtCourse->giftedByMonthId == null)
                    array_push($allCourseMonths, $boughtCourse);
            }

            $gifts = BoughtCourses::find()
                ->where(['userId' => $userId, 'giftedByMonthId' => $monthId])
                ->with('course')
                ->with('month')
                ->all();
        }
        else
        {
            $type = null;
            $stream = null;
            $streamMonths = null;
            $allCourseMonths = null;
            $firstMonth = null;
            $isNew = false;
            $gifts = null;
        }

        return $this->renderAjax('updateMonthUser', [
            'user' => $user,
            'type' => $type,
            'stream' => $stream,
            'streamMonths' => $streamMonths,
            'allCourseMonths' => $allCourseMonths,
            'isSubscription' => $isSubscription,
            'firstMonth' => $firstMonth,
            'isNew' => $isNew,
            'isGifted' => $isGifted,
            'giftParent' => $giftParent,
            'gifts' => $gifts,
            'currentBC' => $currentBC,
            'params' => [
                'userId' => $userId,
                'courseId' => $courseId,
                'monthId' => $monthId,
            ],
            'currentMonth' => $currentMonth,
        ]);
    }

    public function actionDeleteMonthUser($userId, $monthId, $new)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if($new == "true")
            UsersStream::deleteAll(['userId' => $userId, 'monthId' => $monthId]);
        else
            BoughtCourses::deleteAll(['userId' => $userId, 'monthId' => $monthId]);

        return ['success' => true];
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
