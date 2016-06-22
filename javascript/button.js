jQuery(document).ready(function() {
//скрипт для кнопки вверх как в ВК
jQuery('body').append('<div class="button-up" style="display: none;opacity: 0.7;height:100%;position: fixed;right: 5px;top: 95%;cursor: pointer;text-align: center;color: #d3dbe4;font-weight: bold;"><img src=\"./javascript/up-arrow.png\"></div>');

jQuery (window).scroll (function () {
if (jQuery (this).scrollTop () > 100) {
jQuery ('.button-up').fadeIn();
} else {
jQuery ('.button-up').fadeOut();
}
});

jQuery('.button-up').click(function(){
jQuery('body,html').animate({
scrollTop: 0
}, 800);
return false;
});

jQuery('.button-up').hover(function() {
jQuery(this).animate({
'opacity':'1',
}).css({'background-color':'#e7ebf0','color':'#6a86a4'});
}, function(){
jQuery(this).animate({
'opacity':'0.7'
}).css({'background':'none','color':'#d3dbe4'});;
});

});