function InitialUIHandler(){
    setMapSquareLocation(userCities[currentCity].loc_x, userCities[currentCity].loc_y);
    uiUpdate();
}
function uiUpdate(){
    var cd = userCities[currentCity];
    $('.cityimage').attr('src', '/assets/img/items/city'+cd.level+'.png');
    $("#city-name").html('City 1');
    $("#res-food").html(cd.r_food);
    $("#res-food").prev().attr('title', 'Food | Max: '+cd.r_max);
    $("#res-gold").html(cd.r_gold);
    $("#res-gold").prev().attr('title', 'Gold | Max: '+cd.r_max);
    $("#res-wood").html(cd.r_wood);
    $("#res-wood").prev().attr('title', 'Wood | Max: '+cd.r_max);
    $("#res-stone").html(cd.r_stone);
    $("#res-stone").prev().attr('title', 'Stone | Max: '+cd.r_max);
}
