$(document).on('click', '.accordion h3.switcher', function(e){
    e.preventDefault();
    var el = $(this).parent().find('.accordion-data');
    if(el.is(':visible')){
        el.slideUp();
    }else{
        el.slideDown();
    }
});
