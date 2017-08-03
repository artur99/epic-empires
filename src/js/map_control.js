$(document)
.on('click', '.arrow', function(e){
    e.preventDefault();
    var id = $(this).attr('id');
    if(id == 'right-arrow'){
        console.log("Moved right...");
        setMapLocation(map.offset_x+20, map.offset_y);
    }else if(id == 'left-arrow'){
        setMapLocation(map.offset_x-20, map.offset_y);
    }else if(id == 'top-arrow'){
        setMapLocation(map.offset_x, map.offset_y-20);
    }else if(id == 'down-arrow'){
        setMapLocation(map.offset_x, map.offset_y+20);
    }
})
.keydown(function(e) {
    var kn = e.which;

    if(kn == 37) //left
        setMapLocation(map.offset_x-20, map.offset_y);
    else if(kn == 38) //up
        setMapLocation(map.offset_x, map.offset_y-20);
    else if(kn == 39) //right
        setMapLocation(map.offset_x+20, map.offset_y);
    else if(kn == 40) //down
        setMapLocation(map.offset_x, map.offset_y+20);
    else return;
    e.preventDefault();
});


function setMapLocation(x, y){
    x = parseInt(x);
    y = parseInt(y);
    x = Math.max(0, x);
    x = Math.min(mapSize*mapSquareSize, x);
    y = Math.max(0, y);
    y = Math.min(mapSize*mapSquareSize, y);
    map.offset_x = x;
    map.offset_y = y;
    $("#game").css('background-position-x', '-'+x+'px')
    $("#game").css('background-position-y', '-'+y+'px')
    drawAll();
}

function setMapSquareLocation(x, y){
    x = (parseInt(x)-0.5)*mapSquareSize;
    y = (parseInt(y)-0.5)*mapSquareSize;
    setMapLocation(x - $("#game").width()/2, y - $("#game").height()/2);
}
