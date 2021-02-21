<?

$payment = \app\models\TinkoffPay::find()
    ->where(['@>', 'response', new \yii\db\JsonExpression(['Success' => false])])
    ->all();

var_dump($payment);