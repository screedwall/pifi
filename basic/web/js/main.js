$(window).scroll(function() {
    if($( window ).width() > 767)
        if ($(this).scrollTop() > 20){
            $('.header').addClass("sticky");
        }
        else{
            $('.header').removeClass("sticky");
        }
});