<?php


namespace app\commands;

use app\controllers\AppController;
use app\controllers\PayController;
use app\models\Courses;
use app\models\Months;
use app\models\Users;
use app\models\BoughtCourses;
use app\models\UsersStream;
use yii\console\Controller;
use yii\console\ExitCode;
use Yii;
use yii\helpers\ArrayHelper;

class RefactorUsersController extends Controller
{
    public function actionIndex()
    {
        //Purge records
        UsersStream::deleteAll();
        BoughtCourses::deleteAll();

        //Restore from backup


        $courses = Courses::find()->asArray()->all();
        $months = Months::find()->asArray()->all();
        $users = Users::find()->asArray()->all();

        $streamArray = [];
        $streamHandle = fopen(Yii::getAlias('@app').'/pgBackup/fromPiFi/refactorUsersPaymentsStreams.csv', "r");
        while(($data = fgetcsv($streamHandle, 1000, ",")) !== false) {
            //data[0] - id
            //data[1] - userId
            //data[2] - courseId
            //data[3] - monthId
            //data[4] - type
            //data[5] - remains

            array_push($streamArray, [
                'userId' => $data[1],
                'courseId' => $data[2],
                'monthId' => $data[3],
                'type' => $data[4],
            ]);
        }
        fclose($streamHandle);

        foreach ($streamArray as $stream)
        {
            if($this->ifIsIn2($stream['userId'], $users) && ($this->ifIsIn2($stream['courseId'], $courses) && $this->ifIsIn2($stream['monthId'], $months)))
                PayController::CreateMonthUser($stream['courseId'], $stream['monthId'], $stream['userId'], $stream['type']);

            echo $stream['courseId']." ".$stream['monthId']." ".$stream['userId']." ".$stream['type'];
        }

        $boughtCourseArray = [];
        $boughtCourseHandle = fopen(Yii::getAlias('@app').'/pgBackup/fromPiFi/refactorUsersPaymentsBC.csv', "r");
        while(($data = fgetcsv($boughtCourseHandle, 1000, ",")) !== false) {
            //data[0] - id
            //data[1] - userId
            //data[2] - courseId
            //data[3] - monthId
            //data[4] - paymentId
            //data[5] - giftedByMonthId
            //data[6] - giftedByBC
            //data[7] - streamId

            array_push($boughtCourseArray, [
                'userId' => $data[1],
                'courseId' => $data[2],
                'monthId' => $data[3],
            ]);
        }
        fclose($boughtCourseHandle);

        foreach ($boughtCourseArray as $bcItem)
        {
            if($this->ifIsIn2($bcItem['userId'], $users) && ($this->ifIsIn2($bcItem['courseId'], $courses) && $this->ifIsIn2($bcItem['monthId'], $months)))
                PayController::CreateMonthUser($bcItem['courseId'], $bcItem['monthId'], $bcItem['userId'], AppController::STREAM_TYPE_MONTH, null, true);

            echo $bcItem['courseId']." ".$bcItem['monthId']." ".$bcItem['userId'];
        }

        echo "DONE";

        return ExitCode::OK;
    }



    function ifIsIn2($needle, $haystack)
    {
        foreach ($haystack as $item)
        {
            if(ArrayHelper::isIn($needle, $item))
                return true;
        }

        return false;
    }
}