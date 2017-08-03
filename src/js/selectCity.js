$(document).on('click', '.city-on-map', function(e){
    e.preventDefault();
    var city_id = $(this).data('id').split('-')[1];
    var my_city = $(this).hasClass('my-city');
    $(".city-div").removeClass('city-selected');
    $(".city-div[data-id=city-"+city_id+"]").addClass('city-selected');
    $(".none-show").hide();
    if(!my_city){
        fillWithEnemyCity(city_id);
    }else{
        fillWithMyCity(city_id);
    }
    $("#selected-city-show").show();
});
function getCityData(city_id){
    for(var id in otherCities){
        if(otherCities[id].id == city_id) return otherCities[id];
    }
    for(var id in userCities){
        if(userCities[id].id == city_id) return userCities[id];
    }
    return false;
}
function fillWithEnemyCity(city_id){
    var city_data = getCityData(city_id);
    var htmlc = '';
    htmlc += '<h2>'+city_data.username+'</h2>'
    htmlc += '<img src="/assets/img/items/city'+city_data.level+'.png" alt="" class="cityimage">';
    htmlc += '<p class="textp">City 1 ('+city_data.points+')</p>';
    $("#selected-city-show").html(htmlc);
}
function fillWithMyCity(city_id){
    var city_data = getCityData(city_id);
    var htmlc = '';
    htmlc += '<h2>'+city_data.username+'</h2>'
    htmlc += '<img src="/assets/img/items/city'+city_data.level+'.png" alt="" class="cityimage">';
    htmlc += '<p class="textp">City 1 ('+city_data.points+')</p>';
    htmlc += '<p class="textp2">- This is your city -</p>';
    $("#selected-city-show").html(htmlc);
}
