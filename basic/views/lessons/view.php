<?php
use lesha724\youtubewidget\Youtube;
/* @var $this yii\web\View */
/* @var $model app\models\Lessons */
?>
<h2>Урок: <?= $model->name ?></h2>
<h4>Урок состоялся: <?= $model->lessonDate ?></h4>

<?php
echo Youtube::widget([
    'video' => $model->video,
    'iframeOptions'=>[ /*for container iframe*/
        'class'=>'embed-responsive-item'
    ],
    'divOptions'=>[ /*for container div*/
        'class'=>'embed-responsive embed-responsive-16by9'
    ],
    'height'=>390,
    'width'=>640,
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
        'disablekb'=>0
    ],
    'events'=>[
        /*https://developers.google.com/youtube/iframe_api_reference?hl=ru*/
        'onReady'=> 'function (event){
                        /*The API will call this function when the video player is ready*/
                        event.target.playVideo();
            }',
    ]
]);