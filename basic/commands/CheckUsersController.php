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

class CheckUsersController extends Controller
{
    public function actionIndex()
    {

        $checkHandle = fopen(Yii::getAlias('@app').'/pgBackup/fromPiFi/refactorUsersPaymentsCheck2.csv', "w");

        $boughtCourseArray = [];
        $boughtCourseHandle = fopen(Yii::getAlias('@app').'/pgBackup/fromPiFi/refactorUsersPaymentsBC.csv', "r");
        while(($data = fgetcsv($boughtCourseHandle, 0, ",")) !== false) {
            //data[0] - id
            //data[1] - userId
            //data[2] - courseId
            //data[3] - monthId
            //data[4] - paymentId
            //data[5] - giftedByMonthId
            //data[6] - giftedByBC
            //data[7] - streamId

            if(empty(BoughtCourses::findOne(['userId' => $data[1], 'courseId' => $data[2], 'monthId' => $data[3]])))
            {
                $course = Courses::findOne($data[2]);
                $month = Months::findOne($data[3]);
                $user = Users::findOne($data[1]);

                if(!empty($user) && $course && $month)
                {
                    fputcsv($checkHandle, [
                        $course->name,
                        $month->name,
                        $user->name,
                        $user->vk,
                        $user->email,
                    ]);
                }
                else
                {
                    fputcsv($checkHandle, [
                        !empty($data[2]) ? $data[2] : $course,
                        !empty($data[3]) ? $data[3] : $month,
                        !empty($data[1]) ? $data[1] : $user,
                    ]);
                }
            }
        }


        fclose($checkHandle);
        fclose($boughtCourseHandle);

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