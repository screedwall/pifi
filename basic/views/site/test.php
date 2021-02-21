<?php
use yii\bootstrap\Html;

echo Html::a('Открыть форму',
                ['/site/update-month-user','id' => 1],
                [
                    'title' => 'View Modal',
                    'data-toggle'=>'modal',
                    'data-target'=>'#updateMonthUser',
                ]
            );

?>

<div class="modal remote fade" id="updateMonthUser">
    <div class="modal-dialog">
        <div class="modal-content loader-lg"></div>
    </div>
</div>
