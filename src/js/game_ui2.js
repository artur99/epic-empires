function InitialUIHandler(){
    setMapSquareLocation(userCities[currentCity].loc_x, userCities[currentCity].loc_y);
    uiUpdate();
    uiBuildingsUpdate();
}
function uiUpdate(){
    var cd = userCities[currentCity];
    $('.cityimage').attr('src', '/assets/img/items/city'+cd.level+'.png');
    $("#city-name").html('City 1 ('+cd.points+')');
    $("#res-food").html(cd.r_food);
    $("#res-food").prev().attr('title', 'Food | Max: '+cd.r_max);
    $("#res-gold").html(cd.r_gold);
    $("#res-gold").prev().attr('title', 'Gold | Max: '+cd.r_max);
    $("#res-wood").html(cd.r_wood);
    $("#res-wood").prev().attr('title', 'Wood | Max: '+cd.r_max);
    $("#res-workers").html(cd.r_workers);
    $("#res-workers").prev().attr('title', 'Free Workers');
}
function uiBuildingsUpdate(){
    var cd = userCities[currentCity];
    $(".buildings .upgrade").addClass('disabled');

    $("#building-barracks").find('.level').html(cd.b_barracks);
    $("#building-barracks").find('.level2').html(parseInt(cd.b_barracks)+1);
    if(cd.b_barracks2.time != -1){
        $("#building-barracks").find('.time').html(cd.b_barracks2.time.toString()+' s');
        $("#building-barracks .upgrade").removeClass('disabled');
    }
    var html = '';
    for(var i in cd.b_barracks2.costs){
        var k = cd.b_barracks2.costs[i]; //each resource
        html += '<img src="/assets/img/items/res_'+i+'.png"> '+k+' &nbsp;'
    }
    $("#building-barracks").find('.res').html(html);

    $("#building-academy").find('.level').html(cd.b_academy);
    $("#building-academy").find('.level2').html(parseInt(cd.b_academy)+1);
    if(cd.b_academy2.time != -1){
        $("#building-academy").find('.time').html(cd.b_academy2.time.toString()+' s');
        $("#building-academy .upgrade").removeClass('disabled');
    }
    var html = '';
    for(var i in cd.b_academy2.costs){
        var k = cd.b_academy2.costs[i]; //each resource
        html += '<img src="/assets/img/items/res_'+i+'.png"> '+k+' &nbsp;'
    }
    $("#building-academy").find('.res').html(html);
}
