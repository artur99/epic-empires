$(document).ready(function(){
    if($('#game').length){
        getUserInfo(function(){
            InitialUIHandler();
            getCitiesAround(map.offset_x/500, map.offset_y/500, function(){
                drawAll();
            });
        });
    }
});
function getUserInfo(cb){
    ajaxPost('/ajax/user/data', {}, function(data){
        userCities = data.cities;
        if(!currentCity)
            currentCity = 0;
        currentCityId = userCities[currentCity].id;
        cb();
    })
}
var l_x_ch = -1000;
var l_y_ch = -1000;
function getCitiesAround(x_sq, y_sq, cb){
    var dif1 = Math.abs(x_sq - l_x_ch);
    var dif2 = Math.abs(y_sq - l_y_ch);
    if(dif1 < 500 && dif2 < 500) return;
    l_x_ch = x_sq;
    l_y_ch = y_sq;
    ajaxPost('/ajax/data/cities', {x: x_sq, y: y_sq}, function(data){
        otherCities = data;
        cb();
    });
}
