$(document).on('click', '.city-on-map', function(e){
    e.preventDefault();
    var city_id = $(this).data('id').split('-')[1];
    var my_city = $(this).hasClass('my-city');
    $(".city-div").removeClass('city-selected');
    $(".city-div[data-id=city-"+city_id+"]").addClass('city-selected');
    $(".none-show, #selected-city-show, #selected-own-city-show").hide();
    selectedCity = city_id;
    if(!my_city){
        fillWithEnemyCity(city_id);
        $("#selected-city-show").show();
    }else{
        fillWithMyCity(city_id);
        $("#selected-own-city-show").show();
    }
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
    $("#selected-city-show").find('.user-name').html(htmlentities(city_data.username)).data('id', city_data.id);
    $("#selected-city-show").find('.cityimage').attr('src', '/assets/img/items/city'+city_data.level+'.png');
    $("#selected-city-show").find('.city-name').html('City 1 ('+city_data.points+')');
    $("#attack-form").find('.upgrade').data('id', 'war-'+city_id);
    var dist = distanceRate * cityDist(currentCityId, city_id);
    var time = parseInt(dist * spmRate);
    $("#attack-form").find('.dist').html(dist+' miles');
    $("#attack-form").find('.time').html(time+' s');

}
function fillWithMyCity(city_id){
    var city_data = getCityData(city_id);
    $("#selected-own-city-show").find('.user-name').html(htmlentities(city_data.username)).data('id', city_data.id);
    $("#selected-own-city-show").find('.cityimage').attr('src', '/assets/img/items/city'+city_data.level+'.png');
    $("#selected-own-city-show").find('.city-name').html('City 1 ('+city_data.points+')');
}
function cityDist(city1_id, city2_id){
    city1data = getCityData(city1_id);
    city2data = getCityData(city2_id);
    var x1 = city1data.loc_x;
    var y1 = city1data.loc_y;
    var x2 = city2data.loc_x;
    var y2 = city2data.loc_y;

    return Math.sqrt((x2-x1)*(x2-x1) + (y2-y1)*(y2-y1));
}
