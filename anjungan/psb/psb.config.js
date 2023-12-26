psb_Konfigurasi = function() {
	
    $.ajax({
        url : 'psb/psb.config.login.php',
        type: 'get',
        success : function(html) {
            $('#psb_content').html(html);
        }
    })
}

psb_Login = function() {
	
    var password = trim($('#psb_password').val());
    if (password.length == 0)
        return;
    
    $.ajax({
        url : 'psb/psb.config.ajax.php?op=doLogin&password='+password,
        type: 'get',
        success : function(html) {
            $('#psb_content').html(html);
        },
		error: function(xhr, options, error) {
			$('#psb_ConfigLoginError').html(xhr.responseText);
		}
    })
}

psb_CancelLogin = function() {
	
    psb_InputPsbClicked();
	
}

psb_CancelConfig = function() {
	
    $.ajax({
        url : 'psb/psb.config.ajax.php?op=doLogout',
        type: 'get',
        success : function(html) {
            psb_InputPsbClicked();
        }
    })
	
}

psb_SaveConfig = function() {
    
	var enable = $("#psb_CheckEnableInput").is(":checked") ? 1 : 0;
    $.ajax({
        url : 'psb/psb.config.save.php?enable='+enable,
        type: 'post',
        success : function(html) {
            psb_InputPsbClicked();
        }
    })
}