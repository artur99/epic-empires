$(document).on('click', '.city-on-map', function(e){
    e.preventDefault();
    alert($(this).data('id'));
});
