<?php
use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use app\controllers\AppController;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $user app\models\Users */
/* @var $firstMonth app\models\Months */
/* @var $gifts app\models\BoughtCourses[] */
/* @var $streamMonths app\models\BoughtCourses[] */
/* @var $allCourseMonths app\models\BoughtCourses[] */
/* @var $currentBC app\models\BoughtCourses */
/* @var $isNew boolean */
/* @var $isSubscription boolean */
/* @var $stream string */
/* @var $type string */
/* @var $giftParent app\models\Months */
/* @var $currentMonth app\models\Months */
/* @var $isGifted boolean */

$isSpec = $currentMonth->course->isSpec;
$diff = [];
if(!empty($streamMonths))
    $diff = array_diff(ArrayHelper::getColumn($allCourseMonths, 'id'), ArrayHelper::getColumn($streamMonths, 'id'));
?>

<?php $form = ActiveForm::begin([
                        'id' => 'update-month-user-form',
                        'action' => ['update-month-user', 'new' => $isNew ? 'true' : 'false'] + $params,
                    ]); ?>

    <div class="modal-body" id="modalBody">

        <div class="form-group">
            <label class="control-label" for="subscriptionType"><?= $isNew ? 'Тип подписки' : 'Продление' ?></label>
            <?php
                $types = [];
                $options = [];
                if(!$isSpec)
                {
                    foreach (AppController::STREAM_TYPES as $STREAM_TYPE) {
                        $types += [$STREAM_TYPE => AppController::getStreamType($STREAM_TYPE)];
                    }

                    if($isNew)
                    {
                        $options += [AppController::STREAM_TYPE_MONTH => ['disabled' => true]];
                        $options += [AppController::STREAM_TYPE_DEMO_MONTH => ['disabled' => true]];
                        $options += [AppController::STREAM_TYPE_SHORT_CONT => ['disabled' => true]];
                        $options += [AppController::STREAM_TYPE_LONG_CONT => ['disabled' => true]];
                    }
                    else
                    {
                        foreach (AppController::STREAM_TYPES as $STREAM_TYPE)
                            $options += [$STREAM_TYPE => ['disabled' => true]];

                        if($isSubscription)
                        {
                            ArrayHelper::remove($options, $stream);
                        }
                        else
                        {
                            ArrayHelper::remove($options, AppController::STREAM_TYPE_MONTH);
                            ArrayHelper::remove($options, AppController::STREAM_TYPE_DEMO_MONTH);
                        }
                    }
                    $value = $isSubscription ? $stream : ($currentBC->isDemo ? AppController::STREAM_TYPE_DEMO_MONTH : AppController::STREAM_TYPE_MONTH);
                }
                else
                {
                    $types = [AppController::STREAM_TYPE_SPEC => 'Спецкурс'];
                    $value = AppController::STREAM_TYPE_SPEC;
                }
            ?>
            <?= \kartik\select2\Select2::widget([
                'id' => 'subTypeModal',
                'name' => 'subscriptionType',
                'value' => $value,
                'data' => $types,
                'options' => [
                    'placeholder' => 'Выберите тип ...',
                    'options' => $options,
               ],
                'pluginEvents' => [
                    "change" => "function() {
                        if(".(empty($diff) ? 'true' : 'false').")
                            $('#modalButton').attr('disabled', false);
                        
                        let val = $(this).select2('data')[0].id;
                        if(val == '".AppController::STREAM_TYPE_DEMO_MONTH."' || val == '".AppController::STREAM_TYPE_DEMO."')
                            $('#demo-group').removeClass('hidden');
                        else
                            $('#demo-group').addClass('hidden');
                    }",
                ],
            ]); ?>
        </div>


        <div id="demo-group" class="form-group<?= $currentBC->isDemo ? null : ' hidden' ?>">
            <div class="checkbox">
                <label>
                    <?= Html::checkbox('isDemoContinued', $currentBC->isDemoContinued, [
                        'onclick' => "$('#modalButton').attr('disabled', false);",
                    ]) ?><span> Демо продлен</span>
                </label>
            </div>
        </div>


        <?php
            if(!$isSpec)
                echo !$isNew ? "<p>Первая покупка в курсе: "
                    ."<a href='".Url::to(['update', 'id' => $firstMonth->id, 'courseId' => $firstMonth->courseId, 'MonthUsersSearch[name]' => $user->name, 'MonthUsersSearch[vk]' => $user->vk, '#' => 'users'])."'>$firstMonth->name</a>"
                    ."</p>"
                    ."<p>Тип оформленной подписки: ".AppController::getStreamType($type).'.'."</p>"
                    ."<p>".($isSubscription ? 'Подписка действует на этот месяц.' : 'Подписка не действует на этот месяц.')."</p>"
                    : null
        ?>

        <?= ($isGifted ? "<p><strong>Этот месяц подарен при покупке месяца: ".Html::a($giftParent->course->name." ".$giftParent->name,
                Url::to(['update',
                    'id' => $giftParent->id,
                    'courseId' => $giftParent->courseId,
                    'MonthUsersSearch[name]' => $user->name,
                    'MonthUsersSearch[vk]' => $user->vk,
                    '#' => 'users']))."</strong></p>"
            : null) ?>

        <?php
            if(!empty($streamMonths))
            {
                echo Html::a('Месяцы подписки', '#subMonths', [
                    'type' => 'button',
                    'data-toggle' => 'collapse',
                    'aria-expanded' => 'false',
                    'aria-controls' => 'subMonths',
                ]);
                echo "<div id='subMonths' class='collapse well'>";
                    foreach ($streamMonths as $streamMonth)
                        echo "<p>".Html::a($streamMonth->month->name, Url::to(['update', 'id' => $streamMonth->month->id, 'courseId' => $streamMonth->month->courseId, 'MonthUsersSearch[name]' => $user->name, 'MonthUsersSearch[vk]' => $user->vk, '#' => 'users']))."</p>";
                echo "</div><br>";
            }
            if(!empty($gifts))
            {
                echo Html::a('Подаренные месяцы за приобретение текущего', '#giftedMonths', [
                    'type' => 'button',
                    'data-toggle' => 'collapse',
                    'aria-expanded' => 'false',
                    'aria-controls' => 'subMonths',
                ]);
                echo "<div id='giftedMonths' class='collapse well'>";
                    foreach ($gifts as $gift)
                        echo "<p>".Html::a($gift->course->name." ".$gift->month->name, Url::to(['update', 'id' => $gift->month->id, 'courseId' => $gift->course->id, 'MonthUsersSearch[name]' => $user->name, 'MonthUsersSearch[vk]' => $user->vk, '#' => 'users']))."</p>";
                echo "</div><br>";
            }
            if(!empty($allCourseMonths))
            {
                if(!empty($diff))
                {
                    echo Html::a('Продления текущего курса (кроме подписки)', '#allCourseMonths', [
                        'type' => 'button',
                        'data-toggle' => 'collapse',
                        'aria-expanded' => 'false',
                        'aria-controls' => 'subMonths',
                    ]);
                    echo "<div id='allCourseMonths' class='collapse well'>";
                        foreach ($allCourseMonths as $courseMonth)
                            if(ArrayHelper::isIn($courseMonth->id, $diff))
                                echo "<p>".Html::a($courseMonth->month->name, Url::to(['update', 'id' => $courseMonth->month->id, 'courseId' => $courseMonth->course->id, 'MonthUsersSearch[name]' => $user->name, 'MonthUsersSearch[vk]' => $user->vk, '#' => 'users']))."</p>";
                    echo "</div>";
                }
            }
        ?>

        <p><?= $isNew ? (!empty($diff) ? 'Нельзя изменить тип подписки если были продления' : null) : 'Изменение типа подписки возможно только в первом купленном месяце' ?></p>


    </div>

    <div class="modal-footer <?= $isSpec ? 'hidden' : null ?>">
        <?= Html::submitButton('Сохранить', [
                'class' => 'btn btn-block btn-success',
                'id' => 'modalButton',
                'disabled' => true,
        ]) ?>
        <div class="alert-zone text-center"></div>
    </div>
<?php ActiveForm::end(); ?>

    <div class="modal-footer">
        <?php
            $isUndeletable = false;
            if(!$isNew)
            {
                if($isGifted)
                {
                    $isUndeletable = true;
                    $reason = 'Нельзя удалить подарок';
                }

                if($isSubscription && !$isSpec)
                {
                    $isUndeletable = true;
                    $reason = 'Нельзя удалить продолжение абонемента';
                }
            }
            else
            {
                if(!empty($diff))
                {
                    $isUndeletable = true;
                    $reason = 'Сначала удалите продления в других месяцах этого курса';
                }
            }
        ?>
        <?=
            Html::button('Удалить', [
                'onclick' => '
                        if(confirm("Пользователь месяца удалится без возможности востановления.\nПродолжить?"))
                            $.ajax({
                                type: "POST",
                                url: "'.Url::to(['delete-month-user', 'userId' => $user->id, 'monthId' => $params['monthId'], 'new' => ($isNew ? 'true' : 'false')]).'",
                            })
                            .done(function(data) {
                                $.pjax.reload({container: "#month-users"});
                                $("#updateMonthUser").modal("hide");
                        });',
                'class' => 'btn btn-block btn-danger',
                'disabled' => $isUndeletable,
                'title' => $isUndeletable ? $reason : 'Удалить',
            ])
        ?>
    </div>

<?php
$formJS = <<<JS

    $('#update-month-user-form').on('beforeSubmit', function () {
        var yiiform = $(this);
        $.ajax({
                type: yiiform.attr('method'),
                url: yiiform.attr('action'),
                data: yiiform.serializeArray()
            }
        )
        .done(function(data) {
           if(data.success) {
               $(".alert-zone").html('<br><div class="alert alert-success alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Данные сохранены успешно.</div>');
               $('#modalButton').attr('disabled', true);
            } else {
               $(".alert-zone").html('<br><div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Ошибка: </strong>'+data.reason+'</div>');
               $('#modalButton').attr('disabled', true);
            }
        })
        .fail(function () {
            $(".alert-zone").html('<br><div class="alert alert-danger alert-dismissible"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Ошибка:</strong> Произошла ошибка передачи данных.</div>');
        })
        return false;
    });
JS;

$this->registerJs($formJS);
