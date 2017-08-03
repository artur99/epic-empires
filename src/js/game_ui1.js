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
        if(i > 3)
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
    for(cityi in userCities){
        var d = userCities[cityi];
        var x1 = (d.loc_x - 0.5) * mapSquareSize;
        var y1 = (d.loc_y - 0.5) * mapSquareSize;
        var clss = '';
        if(d.id == currentCityId){
            clss ='city-selected';
        }
        drawItem('city'+d.level, x1, y1, 'city-on-map my-city', 'city-div my-city '+clss, 'city-'+d.id);
    }
}
function drawOtherCities(x, y, lvl){
    for(cityi in otherCities){
        var d = otherCities[cityi];
        if(d.user_id == user_id) continue;
        var x1 = (d.loc_x - 0.5) * mapSquareSize;
        var y1 = (d.loc_y - 0.5) * mapSquareSize;
        drawItem('city'+d.level, x1, y1, 'city-on-map other-city', 'city-div other-city', 'city-'+d.id);
    }
}





function drawAll(){
    $("#elements").html('');
    drawTrees();
    // draw();
    drawMyCities();
    drawOtherCities();
}
