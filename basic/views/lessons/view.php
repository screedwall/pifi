<?php
use lesha724\youtubewidget\Youtube;
use yii\bootstrap\Html;
use yii\httpclient\Client;

/* @var $this yii\web\View */
/* @var $model app\models\Lessons */
$this->title = $model->name;

$js = "
    function resizeIframe(obj) {
        var mobile = (/iphone|ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));  
        if (mobile) { 
            obj.parentElement.parentElement.style.display = 'none';
            obj.parentElement.parentElement.previousElementSibling.className = 'col-md-12';
        } 
        obj.height = $('#wyoutube0').outerHeight();
        obj.width = obj.parentElement.clientWidth;
      }
";

$this->registerJs($js, \yii\web\View::POS_HEAD);

?>
<h2>Урок: <?= $model->name ?></h2>
<h4><?= new \DateTime() >= date_create_from_format('d.m.Y H:i', $model->lessonDate) ? "Урок состоялся: ".$model->lessonDate : "Урок будет: ".$model->lessonDate ?></h4>

<?php if(count($model->attachments) > 0): ?>
    <h3 class="text-center">Материалы к уроку</h3>
    <div class="attachments half">
        <?php foreach ($model->attachments as $attachment): ?>

            <div class="attachment-card">
                <div class="row">
                    <div class="col-md-2 attachment-file">
                        <i class="glyphicon glyphicon-file"></i>
                    </div>
                    <div class="col-md-7 attachment-name">
                        <p><?=$attachment->name?></p>
                    </div>
                    <div class="col-md-3 attachment-action">
                        <?=Html::a('<i class="glyphicon glyphicon-download-alt"></i> Скачать', \yii\helpers\Url::to(['lessons/download', 'id' => $attachment->id]), [
                            'class' => 'btn btn-primary btn-block',
                        ])?>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
    </div>
    <br>
<?php endif; ?>

<h3><?=  $model->description ?></h3>

<?php
if(!empty($model->videos))
    foreach ($model->videos as $video)
        echo Youtube::widget([
            'video' => $video->url,
            'iframeOptions'=>[ /*for container iframe*/
                'class'=>'embed-responsive-item'
            ],
            'divOptions'=>[ /*for container div*/
                'class'=>'embed-responsive embed-responsive-16by9'
            ],
            'height' => 390,
            'width' => 640,
            'playerVars'=>[
                /*https://developers.google.com/youtube/player_parameters?playerVersion=HTML5&hl=ru#playerapiid*/
                /*	Значения: 0, 1 или 2. Значение по умолчанию: 1. Этот параметр определяет, будут ли отображаться элементы управления проигрывателем. При встраивании IFrame с загрузкой проигрывателя Flash он также определяет, когда элементы управления отображаются в проигрывателе и когда загружается проигрыватель:*/
                'controls' => 1,
                /*Значения: 0 или 1. Значение по умолчанию: 0. Определяет, начинается ли воспроизведение исходного видео сразу после загрузки проигрывателя.*/
                'autoplay' => 0,
                /*Значения: 0 или 1. Значение по умолчанию: 1. При значении 0 проигрыватель перед началом воспроизведения не выводит информацию о видео, такую как название и автор видео.*/
                'showinfo' => 0,
                /*Значение: положительное целое число. Если этот параметр определен, то проигрыватель начинает воспроизведение видео с указанной секунды. Обратите внимание, что, как и для функции seekTo, проигрыватель начинает воспроизведение с ключевого кадра, ближайшего к указанному значению. Это означает, что в некоторых случаях воспроизведение начнется в момент, предшествующий заданному времени (обычно не более чем на 2 секунды).*/
                'start'   => 0,
                /*Значение: положительное целое число. Этот параметр определяет время, измеряемое в секундах от начала видео, когда проигрыватель должен остановить воспроизведение видео. Обратите внимание на то, что время измеряется с начала видео, а не со значения параметра start или startSeconds, который используется в YouTube Player API для загрузки видео или его добавления в очередь воспроизведения.*/
                'end' => 0,
                /*Значения: 0 или 1. Значение по умолчанию: 0. Если значение равно 1, то одиночный проигрыватель будет воспроизводить видео по кругу, в бесконечном цикле. Проигрыватель плейлистов (или пользовательский проигрыватель) воспроизводит по кругу содержимое плейлиста.*/
                'loop ' => 0,
                /*тот параметр позволяет использовать проигрыватель YouTube, в котором не отображается логотип YouTube. Установите значение 1, чтобы логотип YouTube не отображался на панели управления. Небольшая текстовая метка YouTube будет отображаться в правом верхнем углу при наведении курсора на проигрыватель во время паузы*/
                'modestbranding'=>  1,
                /*Значения: 0 или 1. Значение по умолчанию 1 отображает кнопку полноэкранного режима. Значение 0 скрывает кнопку полноэкранного режима.*/
                'fs' => 1,
                /*Значения: 0 или 1. Значение по умолчанию: 1. Этот параметр определяет, будут ли воспроизводиться похожие видео после завершения показа исходного видео.*/
                'rel' => 0,
                /*Значения: 0 или 1. Значение по умолчанию: 0. Значение 1 отключает клавиши управления проигрывателем. Предусмотрены следующие клавиши управления.
                    Пробел: воспроизведение/пауза
                    Стрелка влево: вернуться на 10% в текущем видео
                    Стрелка вправо: перейти на 10% вперед в текущем видео
                    Стрелка вверх: увеличить громкость
                    Стрелка вниз: уменьшить громкость
                */
                'disablekb' => 1
            ],
            'events'=>[
                /*https://developers.google.com/youtube/iframe_api_reference?hl=ru*/
                'onReady'=> 'function (event){
                                /*The API will call this function when the video player is ready*/
                                event.target.playVideo();
                    }',
            ]
        ]);
else
    echo "<h3>Скоро тут будет запись</h3>";
?>