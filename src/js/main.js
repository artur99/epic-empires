function getFormData(form){
    form = $(form);

    var disabled = form.find('input:disabled').removeAttr('disabled');
    var obj1 = form.serializeArray();
    disabled.attr('disabled','disabled');

    var obj2 = {};
    for(var i=0;i<obj1.length;i++){
        obj2[obj1[i].name] = obj1[i].value;
    }
    return obj2;
}
function clearFormOfData(form){
    form = $(form);
    $(form).find("input:not([type=submit])").val("");
    $(form).find("select").val("");
    $(form).find("textarea").html("");
}
function parseAjaxData(data){
    var res = {
        type: 'error',
        text: null,
        title: null
    };
    try{
        if(typeof data.type == 'string' && data.type == 'success')
            res.type = 'success';
        if(typeof data.text == 'string')
            res.text = data.text;
        if(typeof data.title == 'string')
            res.title = data.title;
    }catch(e){
        //nimic?
    }
    return res;
}
function ajaxAlertHandler(data, succ_cb, err_cb){
    try{
        if(typeof data.title != 'string')
            data.title = null;

        if(data.type == 'success'){
            succ_txt(data.text, data.title);

            if(typeof succ_cb == 'function')
                succ_cb();
        }else{
            if(data.type == 'error'){
                error_txt(data.text, data.title);
            }else{
                error();
            }
            if(typeof err_cb == 'function')
                err_cb();
        }
    }catch(e){
        error();
        if(typeof err_cb == 'function')
            err_cb();
    }
}

function error(){
    swal('Error', 'We\'ve got an error! Please try again later.', 'error');
}
function error_txt(txt, title){
    if(typeof title != 'string')
        title = 'Sorry!';
    if(txt[txt.length-1] != '.' && txt[txt.length-1] != '!')
        txt +='.';
    swal(title, txt, 'error');
}
function info_txt(txt, title){
    if(typeof title != 'string')
        title = 'Hi!';
    if(txt[txt.length-1] != '.' && txt[txt.length-1] != '!')
        txt +='.';
    swal(title, txt, 'info');
}
function succ_txt(txt, title){
    if(typeof title != 'string')
        title = 'Done!';
    if(txt[txt.length-1] != '.' && txt[txt.length-1] != '!')
        txt +='.';
    swal(title, txt, 'success');
}
function reload(time, loc){
    if(typeof time == 'undefined')
        time = 1000;

    setTimeout(function(){
        if(typeof loc != 'undefined')
            window.location.href = loc;
        else
            window.location.reload();
    }, time);
}
function ajaxPost(url, data, callback){
    data.csrftoken = csrftoken;
    $.post(url, data, callback);
}
