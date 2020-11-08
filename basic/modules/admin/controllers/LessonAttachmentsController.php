<?php

namespace app\modules\admin\controllers;

use app\models\LessonAttachments;
use yii\web\UploadedFile;
use Yii;

class LessonAttachmentsController extends \yii\web\Controller
{
    public function actionUpload()
    {
        $error = false;

        if (Yii::$app->request->isAjax) {
            $lessonId = Yii::$app->request->post('lessonId');
            $files = UploadedFile::getInstancesByName('files');
            foreach ($files as $file)
            {
                $model = new LessonAttachments();
                $path = "uploads/attachments/lessons/".Yii::$app->security->generateRandomString(13).".$file->extension";
                if($file->saveAs($path))
                {
                    $model->name = $file->name;
                    $model->path = $path;
                    $model->lessonId = $lessonId;
                    $model->save();
                }
            }
            if(!$error)
                return json_encode("YES");
        }
        return "NAY";
    }

    public function actionDelete()
    {
        if (Yii::$app->request->isAjax) {
            $key = Yii::$app->request->post('key');
            $attachment = \app\models\LessonAttachments::findOne(['id' => $key]);
            $attachment->delete();
            return json_encode("OK");
        }
        return "err";
    }

}
