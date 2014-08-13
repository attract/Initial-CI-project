var controller;
var UPLOAD_PATH;
$(function () {
    controller = SITE_URL + 'backend/user/';
    UPLOAD_PATH = BASE_URL + 'media/upload/avatar/';

    //update_form();

    $('select[name=country_id]').live("change", function () {

        url_submit = controller + 'get_city';

        var container = $('select[name=city_id]');

        $.post(url_submit, {id_country:$(this).val()},
            function (data) {
                container.html(data);
                $('select[name=city]').select2().change();

            });
    });

    $('#search_user').keyup(function () {
        do_filter_user();
    });
    $('#conteiner_user').delegate('.pagination ul li a', 'click', function () {
        do_filter_user($(this).attr("href"));
        return false;
    });
});
function do_filter_user(url) {

    var url = url || false;
    if (!url) {
        var keywords = str_replace(' ', '+', $('#search_user').val());
        var company = str_replace(' ', '+', $('#company').val());
        var url = SITE_URL + 'backend/user/list_user/'+$('#company').val()+'/?keyword=' + keywords+'&company='+company;
    }
    change_url(url);
    $('.loader').show();
    $('#conteiner_user').load(url,false,function(){
        $('.loader').hide();
    });
}

function login_as_user(id_user)
{
    var id_user = id_user || false;
    if (!id_user) {
        popup_show('Notification','!Empty user!');
        return false;
    }

    $.post(controller + 'login_as_user',
        {id_user:id_user},
        function (data) {
            popup_close();
            if (data.success == 1) {
                view_growl('You are loged in','Notification');
                window.open(SITE_URL);
                //$('#link_to_site_url').click();

            }
            else{
                view_growl('Cannot login','Error');
                return;
            }

        }, 'json');
}

function activate_user(id_user) {
    var id_user = id_user || false;
    if (!id_user) {
        popup_show('Notification','Empty user!');
        return false;
    }

    $.post(controller + 'activate_user',
        {id_user:id_user},
        function (data) {
            popup_close();
            if (data.status == 1) {
                $('tr#user_' + id_user).removeClass('error');
                $('tr#user_' + id_user + ' .verification').html('yes');
                $('.activate_user_block_' + id_user).remove();

            }
            else{
                popup_show('Notification','Error');
                return;
            }
        }, 'json');
}

function change_user_rights(id_user, new_status) {

    var id_user = id_user || false;
    if (!id_user) {
        popup_show('Notification','Empty user!');
        return false;
    }

    $.post(controller + 'change_user_rights',
        {id_user:id_user,
            new_status:new_status},
        function (data) {
            popup_close();
            if (data.status == 0) {
                popup_show('Notification','Error');
                return;
            }
            if(new_status==1){
                $('.change_user_rights_' + id_user).attr('onclick','change_user_rights("'+id_user+'",2); return false;');
                $('.change_user_rights_' + id_user).html('Set as partner');

                view_growl('User updated as company admin','Notification');
            }
            else{
                $('.change_user_rights_' + id_user).attr('onclick','change_user_rights("'+id_user+'",1); return false;');
                $('.change_user_rights_' + id_user).html('Set as admin');

                view_growl('User updated as company partner','Notification');
            }
            if ($('tr#user_' + id_user).size()==0){
                location.href = controller + 'list_user';
            }
        }, 'json');

}

function change_block_status_user(id_user, new_status) {

    var id_user = id_user || false;
    if (!id_user) {
        popup_show('Notification','Empty user!');
        return false;
    }

    $.post(controller + 'change_block_status_user',
        {id_user:id_user,
        new_status:new_status},
        function (data) {
            popup_close();
            if (data.status == 0) {
                popup_show('Notification','Error');
                return;
            }
            if(new_status==1){
                $('tr#user_' + id_user).addClass('error');
                $('.change_status_' + id_user).attr('onclick','change_block_status_user("'+id_user+'",0); return false;');
                $('.change_status_' + id_user).html('Unblock user');

                view_growl('User is blocked','Notification');
            }
            else{
                if($('tr#user_' + id_user+'.error').size()){
                    $('tr#user_' + id_user).removeClass('error');
                    $('.change_status_' + id_user).attr('onclick','change_block_status_user("'+id_user+'",1); return false;');
                    $('.change_status_' + id_user).html('Block user');
                }
                view_growl('User unblocked','Notification');
            }
            if ($('tr#user_' + id_user).size()==0){
                location.href = controller + 'list_user';
            }
        }, 'json');

}
function add_edit_user(type) {
    type = type || "edit_user_backend";
    var id_user = $('#id_user').val();
    if (type == "edit_user_backend") var method = 'edit_user/' + id_user;
    if (type == "add_user_backend") var method = 'add_user';
    $.post(controller + method, $('#FormAddUser').serialize(),
        function (data) {
            //alert(text);initUploader();
            $('#index-content').html(data.data);
            //update_form();
            var pos = $('#FormAddUser').eq(0).offset();
            if (data.status==1){
                $('body,html').animate({scrollTop:0}, 1000);
                location.href=SITE_URL+'backend/user/list_user';
            }
        }, 'json');

}
/*
function update_form()
{
    $('#FormAddUser').find('input[type=checkbox],input[type=radio],input[type=file]').uniform();

    $('#FormAddUser').find('select').select2();
    initUploader();
}*/

/*
 * функцяяи инициализирует загрузчик
 * */
function initUploader() {
    if ($('#avatar_user_button').size() > 0) {
        //загркзка картинок при создании/редактировании события
        var uploader = new qq.FileUploader({
            element:document.getElementById('avatar_user_button'),
            action:controller + 'do_multiupload',
            debug:true,
            onSubmit:function (id, fileName) {
                loader('show');
            },
            onComplete:function (id, fileName, responseJSON) {

                if (responseJSON.file_name) {
                    $('li.qq-upload-success').remove();
                    var addPict = '<div class="cont_multiupl">' +
                        '<img src="' + UPLOAD_PATH + '100_' + responseJSON.file_name + '">' +
                        '<button class="btn btn-danger btn-mini" type="button" onclick="delete_image(\'' + responseJSON.file_name + '\')">Delete</button>' +
                        '</div>';
                    $('#avatar_user_result').append(addPict);
                    $('input[name="avatar_user"]').val(responseJSON.file_name);
                    check_img('#avatar_user_result', '#avatar_user_button', 1);
                }
                else {
                    $('li.qq-upload-success').remove();
                    $('.qq-upload-list').remove();
                }
                loader('hide');

            },
            onProgress:function (id, fileName, loaded, total) {

                $('li.qq-upload-success').remove();

            }

        });
        check_img('#avatar_user_result', '#avatar_user_button', 1);
    }
}
function check_img(blok_result, blok_button, count) {
    blok_result = blok_result || '#avatar_user_result';
    blok_button = blok_button || '#avatar_user_button';
    count = count || 1;
    if ($(blok_result).find('div.cont_multiupl').size() >= count)
        $(blok_button).hide();
    else
        $(blok_button).show();

}
function delete_image(name_pic) {
    div_foto = "#avatar_user_result";
    id_block = "#avatar_user_button";
    server_funct = "delete_multiupload";
    var name_pic = name_pic || false;
    if (!name_pic) {
        alert('No delete image');
        return false;
    }
    $.ajax({
        type:"POST",
        url:controller + server_funct,
        cache:false,
        data:{'name_pic':name_pic},
        dataType:"json",
        beforeSend:loader('show'),
        success:function (data) {
            loader('hide')
            $(div_foto).find('img[src*="' + name_pic + '"]').closest('div.cont_multiupl').remove();
            check_img('#avatar_user_result', '#avatar_user_button', 1);
            $('.qq-upload-list').remove();

        }
    });

}



function send_lost_pass(id)
{
    var id=id||false;
    if(id)
    {
        $.post(controller+'lost_pass',{id_user:id},function(data)
        {
            if(data=='yes')
            {
                popup_show('Result','New pass was send!','Close','popup_close()');
            }

        });
    }
}
