<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\Users;
use yii\console\Controller;
use yii\console\ExitCode;
use Yii;
/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex($message = 'hello world')
    {
        $model = new Users();

        $handle = fopen('"C:\Program Files\Ampps\www\basic\commands\users_export.csv"', "r");
        while (($fileop = fgetcsv($handle, 1000, ",")) !== false)
        {
            $name = $fileop[0];
            $age = $fileop[1];
            $location = $fileop[2];
//            $sql = "INSERT INTO details(name, age, location) VALUES ('$name', '$age', '$location')";
//            $query = Yii::$app->db->createCommand($sql)->execute();
            echo $name;
        }

        if ($query)
        {
            echo "data upload successfully";
        }

        $model->save();

        return ExitCode::OK;
    }
}
