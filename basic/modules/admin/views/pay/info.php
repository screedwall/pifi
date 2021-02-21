<?php

/* @var $model \app\models\TinkoffPay */

?>

<div class="modal-body">
    <pre>
        <?php
            foreach ($model->response as $key => $value)
                echo "<p>".$key.": ".$value."</p>";
        ?>
    </pre>
</div>
