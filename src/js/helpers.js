function rand(min, max, no, series){
    var seed1 = 23;
    var seed2 = 0.297267;
    var seed3 = 22;
    if(typeof series != 'string'){
        series = 'main';
    }
    var rs = 0;
    for(sr in series)
        rs += series.charCodeAt(sr)*sr;

    no++;
    no = ((no * seed1) % seed3) * (seed1/seed2) - seed2 * no;
    if(no <= 0) no = 1+Math.abs(no);
    var nr1 = no*rs + rs / (seed1 / no * seed2) * 500;
    var fin = ((nr1 - parseInt(nr1)) * 13 - seed2*seed2*no)/100;
    var fin = (nr1 - parseInt(nr1));
    var dif = parseInt((max-min) * fin);
    return dif + min;
}
function htmlentities(txt){
    return $('<div>').html(txt).text();
}
