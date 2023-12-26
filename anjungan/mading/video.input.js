vidinp_CheckMsgLength = function() {
	
	var msg = $("#vidinp_pesan").val();
	var length = $.trim(msg).length;
	var remain = vidinp_MaxNotesLength - length;

	if (remain < 0)
	{
		remain = 0;
		msg = msg.substring(0, vidinp_MaxNotesLength);
	}
	
	$("#vidinp_sisa").val(remain);
	$("#vidinp_pesan").val(msg);
}

vidinp_GetFileExtension = function(name) {
	
    var found = String(name).lastIndexOf(".") + 1;
    return (found > 0 ? name.substr(found) : "");
}

vidinp_CheckVideoType = function(ext) {

	ext = ext.toLowerCase();	
	var found = false;
	for(var i = 0; !found && i < vidinp_AllowedVideoType.length; i++)
	{
		found = ext == vidinp_AllowedVideoType[i];
	}
	
	return found;
}

vidinp_ValidateNotes = function() {
	
	var isok = Validator.CheckLength($("#vidinp_judul"), "Judul Video", 3, 100) &&
			   Validator.CheckLength($("#vidinp_pesan"), "Keterangan", 3, vidinp_MaxNotesLength);
	
	if (!isok)
		return;
		
	var file = $("#vidinp_video_file").val();
	if ($.trim(file).length == 0)
	{
        alert('Anda belum menentukan file video!');
		return;
    }
		
	ext = "." + vidinp_GetFileExtension(file);
	if (!vidinp_CheckVideoType(ext))
	{
		alert(file + ' bukan file video yang diperbolehkan!');
		return;
	}
	
	var login = $.trim($("#vidinp_Login").val());
    var password = $.trim($("#vidinp_Password").val());
	var dept = $.trim($vid_jQueryParent("#mad_departemen").val());
	if (login.length == 0 || password.length == 0)
	{
		alert("Anda belum mengisikan login atau password!");
		return;
	}
		
	$.ajax({
		type: 'POST',
		url: 'video.input.ajax.php',
		data: 'op=validatelogin&dept='+dept+'&login='+login+'&password='+password,
		success: function(html) {
			vidinp_SaveGallery();
		},
		error: function(xhr, response, error) {
			alert(xhr.responseText);
		}
	})
	
}

vidinp_SaveGallery = function() {
	
	var formData = new FormData();
	formData.append("departemen", $vid_jQueryParent("#mad_departemen").val());
	formData.append("login", $.trim($("#vidinp_Login").val()));
	formData.append("password", $.trim($("#vidinp_Password").val()));
	formData.append("judul", $("#vidinp_judul").val());
	formData.append("keterangan", mad_ChangeNewLine($("#vidinp_pesan").val()));
	
	formData.append("video_file", $("#vidinp_video_file")[0].files[0]);
	formData.append("video_info", "");
		
	$("#vidinp_WaitBox").css({"visibility":"visible"});
	$('#vidinp_SaveButton').attr("disabled", true);

	$.ajax({
        url: "video.input.save.php",
        type: 'POST',
        data: formData,
        async: false,
		cache: false,
        contentType: false,
        processData: false,
        success: function (response)
		{
			$("#vidinp_WaitBox").css({"visibility":"hidden"});
			$("#vidinp_content").html(response);
			
			vidinp_BackToVideoList(true);
        },
		error: function (xhr, response, error)
		{
			$('#vidinp_SaveButton').attr("disabled", false);
			$("#vidinp_WaitBox").css({"visibility":"hidden"});
			
			alert(xhr.responseText);
		}
    });
	
}

vidinp_BackToVideoList = function(showtop)
{
	vidlst_RefreshVideoList();
	if (showtop)
		vid_ShowVideoList_TopPos();
	else
		vid_ShowVideoList_LastPos();
}