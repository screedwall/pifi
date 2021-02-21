<?php

use app\models\BoughtCourses;
use app\models\UsersStream;
use yii\db\Migration;

/**
 * Class m210219_222201_refactor_users_payments
 */
class m210219_222201_refactor_users_payments extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $boughCoursesArray = [];
        //Backup current records
        $streams = UsersStream::find()->all();
        $streamHandle = fopen(Yii::getAlias('@app').'/pgBackup/refactorUsersPaymentsStreams.csv', "w");
        foreach ($streams as $stream)
            fputcsv($streamHandle, $stream->attributes);
        fclose($streamHandle);

        $boughtCourses = BoughtCourses::find()
                    ->joinWith(['month' => function($q){
                        return $q->joinWith('course');
                    }])
                    ->joinWith('user')
                    ->all();
        $bcHandle = fopen(Yii::getAlias('@app').'/pgBackup/refactorUsersPaymentsBC.csv', "w");
        foreach ($boughtCourses as $boughtCourse)
        {
            array_push($boughCoursesArray, [
                'userId' => $boughtCourse->userId,
                'monthId' => $boughtCourse->monthId,
                'user' => $boughtCourse->user->name,
                'vk' => $boughtCourse->user->vk,
                'email' => $boughtCourse->user->email,
                'course' => $boughtCourse->month->course->name,
                'month' => $boughtCourse->month->name,
            ]);
            fputcsv($bcHandle, $boughtCourse->attributes);
        }
        fclose($bcHandle);

        //Purge records
        UsersStream::deleteAll();
        BoughtCourses::deleteAll();

        //Restore with new rules
        $streamHandle = fopen(Yii::getAlias('@app').'/pgBackup/refactorUsersPaymentsStreams.csv', "r");
        while(($data = fgetcsv($streamHandle, 1000, ",")) !== false) {
            //data[0] - id
            //data[1] - userId
            //data[2] - courseId
            //data[3] - monthId
            //data[4] - type
            //data[5] - remains

            \app\controllers\PayController::CreateMonthUser($data[2], $data[3], $data[1], $data[4]);
        }
        fclose($streamHandle);

        $checkHandle = fopen(Yii::getAlias('@app').'/pgBackup/refactorUsersPaymentsCheck.csv', "w");
        $newBoughtCourses = BoughtCourses::find()->all();
        foreach ($boughCoursesArray as $bcCheck)
        {
            $flag = true;
            foreach ($newBoughtCourses as $newBoughtCourse)
                if($newBoughtCourse->userId == $bcCheck['userId'] && $newBoughtCourse->monthId == $bcCheck['monthId'])
                    $flag = false;

            if($flag)
                fputcsv($checkHandle, [
                    $bcCheck['course'],
                    $bcCheck['month'],
                    $bcCheck['user'],
                    $bcCheck['vk'],
                    $bcCheck['email'],
                ]);
        }
        fclose($checkHandle);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //Purge records
        UsersStream::deleteAll();
        BoughtCourses::deleteAll();

        //Restore from backup
        $streamArray = [];
        $streamHandle = fopen(Yii::getAlias('@app').'/pgBackup/refactorUsersPaymentsStreams.csv', "r");
        while(($data = fgetcsv($streamHandle, 1000, ",")) !== false) {
            //data[0] - id
            //data[1] - userId
            //data[2] - courseId
            //data[3] - monthId
            //data[4] - type
            //data[5] - remains

            array_push($streamArray, [
                'id' => $data[0],
                'userId' => $data[1],
                'courseId' => $data[2],
                'monthId' => $data[3],
                'type' => $data[4],
                'remains' => empty($data[5]) ? null : $data[5],
            ]);
        }
        fclose($streamHandle);

        $cols = (new UsersStream())->attributes();
        if(!empty($streamArray))
            Yii::$app->db->createCommand()->batchInsert(UsersStream::tableName(), $cols, $streamArray)->execute();

        $boughtCourseArray = [];
        $boughtCourseHandle = fopen(Yii::getAlias('@app').'/pgBackup/refactorUsersPaymentsBC.csv', "r");
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
                'id' => $data[0],
                'userId' => $data[1],
                'courseId' => $data[2],
                'monthId' => $data[3],
                'paymentId' => empty($data[4]) ? null : $data[4],
                'giftedByMonthId' => empty($data[5]) ? null : $data[5],
                'giftedByBC' => empty($data[6]) ? null : $data[6],
                'streamId' => empty($data[7]) ? null : $data[7],
            ]);
        }
        fclose($boughtCourseHandle);

        $cols = (new BoughtCourses())->attributes();
        if(!empty($boughtCourseArray))
            Yii::$app->db->createCommand()->batchInsert(BoughtCourses::tableName(), $cols, $boughtCourseArray)->execute();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210219_222201_refactor_users_payments cannot be reverted.\n";

        return false;
    }
    */
}
