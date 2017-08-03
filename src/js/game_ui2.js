function InitialUIHandler(){
    $('.cityimage').attr('src', '/assets/img/items/city'+userCities[currentCity].level+'.png');
    setMapSquareLocation(userCities[currentCity].loc_x, userCities[currentCity].loc_y);
    $("#city-name").html('City 1');
}
