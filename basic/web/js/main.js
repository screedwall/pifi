$(window).scroll(function() {
    if($( window ).width() > 767)
        if ($(this).scrollTop() > 20){
            $('.header').addClass("sticky");
        }
        else{
            $('.header').removeClass("sticky");
        }
});

function ping() {
    $.ajax({
        url: "/site/ping",
    });
}
$( document ).ready(function () {
    setInterval(function(){
        ping();
    }, 30000);
});