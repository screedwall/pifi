<?php
/* @var $this yii\web\View*/
/* @var $model app\models\Courses */
use yii\helpers\Url;
$this->title = "Курсы";
?>

    <h1>Курсы</h1>
    <div class="row">
    <?php
        $k = 0;
        foreach ($model as $item) : ?>
            <?php
            if($k == 0)
                echo "<div class=\"row display-flex\">";

                $k++;?>
            <div class="col-md-4 col-sm-6">
                <div class="thumbnail">
                    <img src="" alt="" class="src">
                    <div class="caption">
                        <h2><?= $item->name ?></h2>
                        <span class="border border-primary"><?= $item->dateFrom." - ".$item->dateTo ?></span>
                        <p><?= $item->subject." [".$item->examType."] / ".$item->teacher->name ?></p>
                        <h4><?= $item->shortDescription ?></h4>
                        <p><?= $item->description ?></p>
                        <span><?= $item->price ?> руб.</span>
                        <?php
                            if(Yii::$app->user->isGuest)
                                {
                                    $action = '/site/login';
                                    $param = "payId";
                                    $label = "Записаться";
                                }
                            else{
                                if(Yii::$app->user->identity->isAdmin()){
                                    $action = '/courses/view';
                                    $param = "id";
                                    $label = "Открыть";
                                }
                                else{
                                    if(count(Yii::$app->user->identity->getCourses()->where(['id' => $item->id])->all()) > 0){
                                        $action = '/courses/view';
                                        $param = "id";
                                        $label = "Открыть";
                                    }else{
                                        $action = '/pay';
                                        $param = "id";
                                        $label = "Записаться";
                                    }
                                }
                            }
                            echo "<a href=\"".Url::to([$action, $param => $item->id])."\" class=\"btn btn-primary\" role=\"button\">".$label."</a>"
                        ?>
                    </div>
                </div>
            </div>
            <?php
            if($k == 3)
            {
                $k = 0;
                echo "</div>";
            }
            ?>
        <?php endforeach; ?>
    </div>