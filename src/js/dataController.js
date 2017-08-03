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
    if(dif1 < 4 && dif2 < 4) return;
    l_x_ch = x_sq;
    l_y_ch = y_sq;
    ajaxPost('/ajax/game/cities', {x: x_sq, y: y_sq}, function(data){
        otherCities = data;
        cb();
    });
}
function getCitiesAroundCoord(x_loc, y_loc, cb){
    var x = parseInt(x_loc / 500);
    var y = parseInt(y_loc / 500);
    getCitiesAround(x, y, cb);
}
var timeint = setInterval(function(){
    var itms = $(".tleft");
    $.each($(".tleft"), function(i, el){
        var te = $(el).data('timee');
        var ct = Math.floor(Date.now() / 1000);
        var tl = (te - ct);
        if(tl >= 0){
            $(el).html(tl.toString() + ' s');
        }
        if(tl <= 0){
            recursiveUiUpdate();
        }
    });
}, 1000);
