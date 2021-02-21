<?php
namespace app\modules\admin\controllers;

use app\models\TinkoffPay;
use app\models\TinkoffPaySearch;
use yii\web\Controller;
use Yii;

class PayController extends Controller
{
    public function actionIndex()
    {
        $searchModel = new TinkoffPaySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionInfo($id)
    {
        $model = TinkoffPay::findOne($id);
        return $this->renderAjax('info', [
            'model' => $model,
        ]);
    }
}