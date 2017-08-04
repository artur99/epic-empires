function drawTrees(){
    if(typeof map.offset_x == 'undefined') return;
    if(typeof map.offset_y == 'undefined') return;

    var map_x = parseInt(map.offset_x / mapSquareSize) + 1;
    var map_y = parseInt(map.offset_y / mapSquareSize) + 1;

    for(var i=0;i<4;i++){
        for(var j=0;j<4;j++){
            // for each square around
            // map_x + i And map_y + j
            if(squareIsInScreen(map_x + i, map_y + j))
                drawTreesInSquare(map_x + i, map_y + j);
        }
    }

}

function drawTreesInSquare(x, y){
    for(var i=0;i<5;i++){
        var rand1 = rand(0, 500, i, 'trees'+x.toString()+y+'1')-40;
        var rand2 = rand(0, 500, i, 'trees'+x.toString()+y+'2')-40;
        if(i <= 3)
            drawOnMapSquare('tree', x, y, rand1, rand2);
        else
            drawOnMapSquare('tree2', x, y, rand1, rand2);
    }
}

function isInScreen(x, y){
    var map_x = map.offset_x;
    var map_y = map.offset_y;
    var map_xw = map.offset_x + $("#game").width();
    var map_yh = map.offset_y + $("#game").height();
    if(
        x+50 >= map_x && x-50 <= map_xw &&
        y+50 >= map_y && y-50 <= map_yh
    ){
        return true;
    }
    return false;
}

function squareIsInScreen(x, y){
    if(x < 1) return false;
    if(y < 1) return false;
    var map_x = parseInt(map.offset_x / mapSquareSize) + 1;
    var map_y = parseInt(map.offset_y / mapSquareSize) + 1;
    var map_xw = parseInt((map.offset_x + $("#game").width()) / mapSquareSize) + 1;
    var map_yh = parseInt((map.offset_y + $("#game").height()) / mapSquareSize) + 1;
    if(
        x < map_x || x > map_xw ||
        y < map_y || y > map_yh
    ){
        return false;
    }
    return true;
}

function drawOnMapSquare(item, map_x, map_y, inner_x, inner_y){
    var loc_x = (map_x-1) * mapSquareSize + inner_x;
    var loc_y = (map_y-1) * mapSquareSize + inner_y;
    if(
        Math.abs(inner_x - 250) < 20 &&
        Math.abs(inner_y - 250) < 20
    ) return;

    if(isInScreen(loc_x, loc_y)){
        // console.log(loc_x, loc_y, '???', isInScreen(loc_x, loc_y));
        drawItem(item, loc_x, loc_y);
    }
}



function drawItem(what, x, y, cls1, cls2, did){
    if(!isInScreen(x, y)) return;
    x = x - map.offset_x;
    y = y - map.offset_y;
    var cls = '';
    var did2 = false;
    if(typeof did == 'string'){
        did2 = did;
    }
    if(typeof cls2 == 'string'){
        $("#elements").append('<div class="map_item '+cls2+'" style="left:'+x+'px;top:'+y+'px;" '+(did2?'data-id="'+did2+'"':'')+'></div>');
    }
    if(typeof cls1 == 'string')
        cls = cls1;
    $("#elements").append('<img class="map_item '+cls+'" src="/assets/img/items/'+what+'.png" style="left:'+x+'px;top:'+y+'px;" '+(did2?'data-id="'+did2+'"':'')+'>');
}

function drawMyCities(){
    for(var cityi in userCities){
        var d = userCities[cityi];
        var x1 = (d.loc_x - 0.5) * mapSquareSize;
        var y1 = (d.loc_y - 0.5) * mapSquareSize;
        var clss = '';
        if(!selectedCity && d.id == currentCityId || selectedCity == d.id){
            clss ='city-selected';
        }
        drawItem('city'+d.level, x1, y1, 'city-on-map my-city', 'city-div my-city '+clss, 'city-'+d.id);
    }
}
function drawOtherCities(x, y, lvl){
    for(var cityi in otherCities){
        var d = otherCities[cityi];
        if(d.user_id == user_id) continue;
        var x1 = (d.loc_x - 0.5) * mapSquareSize;
        var y1 = (d.loc_y - 0.5) * mapSquareSize;
        var clss = '';
        if(selectedCity == d.id){
            clss ='city-selected';
        }
        drawItem('city'+d.level, x1, y1, 'city-on-map other-city', 'city-div other-city '+clss, 'city-'+d.id);
    }
}
function drawAttacks(){
    $(".attack_icon").remove();
    for(attack in commingAttacks){
        if(commingAttacks[attack]){
            var d = commingAttacks[attack];
            var my_city = getCityData(currentCityId);
            var my_city_x = (my_city.loc_x - 0.5) * mapSquareSize;
            var my_city_y = (my_city.loc_y - 0.5) * mapSquareSize;
            var enemy_city_x = (d.loc_x - 0.5) * mapSquareSize;
            var enemy_city_y = (d.loc_y - 0.5) * mapSquareSize;
            var time_left = d.time_e - parseInt(new Date() / 1000);
            if(time_left <= 0){
                commingAttacks[attack] = false;
                drawAll();
                continue;
            }
            // console.log(my_city_x, my_city_y, enemy_city_x, enemy_city_y);
            var prec = (0.6 + 0.4*((180 - time_left) / 180));
            var real_x = (my_city_x * prec + enemy_city_x * (1-prec));
            var real_y = (my_city_y * prec + enemy_city_y * (1-prec));

            drawItem('army2', real_x, real_y, 'attack_icon');

        }
    }

    lone[2] = JSON.stringify(commingAttacks);
    if(lone[2] != lone[1]){
        lone[1] = lone[2];
        var html1 = '';
        for(attack in commingAttacks){
            var d = commingAttacks[attack];
            html1+='<div data-id="task-'+d.id+'">';
            html1+='<img src="/assets/img/items/army2.png" class="atkm-icon" alt="">';
            html1+='<p>- Unknown -<br>Left: <span class="tleft" data-timee="'+d.time_e+'">-</span></p>';
            html1+='</div>';
        }
        $(".in-attacks .accordion-data").html(html1);
        if(!commingAttacks || commingAttacks.length == 0){
            $(".in-attacks .accordion-data").html('<p>No runnig task...</p>');
        }
    }
    for(attack in goingAttacks){
        if(goingAttacks[attack]){
            var d = goingAttacks[attack];
            var my_city = getCityData(currentCityId);
            var my_city_x = (my_city.loc_x - 0.5) * mapSquareSize;
            var my_city_y = (my_city.loc_y - 0.5) * mapSquareSize;
            var enemy_city_x = (d.loc_x - 0.5) * mapSquareSize;
            var enemy_city_y = (d.loc_y - 0.5) * mapSquareSize;
            var time_left = d.time_e - parseInt(new Date() / 1000);
            if(time_left <= 0){
                goingAttacks[attack] = false;
                drawAll();
                continue;
            }
            var time_total = d.time_e - d.time_s;
            var prec = time_left / time_total;
            var real_x = (my_city_x * (prec) + enemy_city_x * (1-prec));
            var real_y = (my_city_y * (prec) + enemy_city_y * (1-prec));

            drawItem('army', real_x, real_y, 'attack_icon');


        }
    }

    lone[4] = JSON.stringify(goingAttacks);

    if(lone[4] != lone[3]){
        lone[3] = lone[4];
        html1 = '';
        for(attack in goingAttacks){
            var d = goingAttacks[attack];
            html1+='<div data-id="task-'+d.id+'">';
            html1+='<img src="/assets/img/items/army.png" class="atkm-icon" alt="">';

            var units = typeof d.param == 'string' ? JSON.parse(d.param) : d.param;
            html1+='<p>Units: ';
            html1+='<img src="/assets/img/items/res_unit.png" class="unit-icon2"> '+units.units+' &nbsp; '
            html1+='<img src="/assets/img/items/res_archer.png" class="unit-icon2"> '+units.archers+' &nbsp; '
            html1+='</p>';
            html1+='<p>Left: <span class="tleft" data-timee="'+d.time_e+'">-</span></p>';
            html1+='</div>';
        }
        $(".out-attacks .accordion-data").html(html1);
        if(!goingAttacks || goingAttacks.length == 0){
            $(".out-attacks .accordion-data").html('<p>No runnig task...</p>');
        }
    }
}



function drawAll(){
    $("#elements").html('');
    drawTrees();
    // draw();
    drawMyCities();
    drawOtherCities();
    drawAttacks();
}
