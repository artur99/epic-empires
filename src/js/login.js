$(document)
.on('submit', '#form-login', function(e){
    e.preventDefault();
    var data = getFormData(this);

    if(data.username.trim().length == 0) return;
    if(data.password.trim().length == 0) return;

    $.post('/ajax/user/login', {
        username: data.username,
        password: data.password,
        csrftoken: csrftoken
    }, function(data){
        data = parseAjaxData(data);
        if(data.type == 'success'){
            reload(1, '/game');
        }else{
            error_txt(data.text, data.title);
        }
    })
})
.on('submit', '#form-signup', function(e){
    e.preventDefault();
    var data = getFormData(this);

    if(data.username.trim().length == 0) return;
    if(data.email.trim().length == 0) return;
    if(data.password.trim().length == 0) return;
    if(data.password2.trim().length == 0) return;

    $.post('/ajax/user/signup', {
        username: data.username,
        email: data.email,
        password: data.password,
        password2: data.password2,
        csrftoken: csrftoken
    }, function(data){
        data = parseAjaxData(data);
        if(data.type == 'success'){
            reload(1, '/game');
        }else{
            error_txt(data.text, data.title);
        }
    })
})
