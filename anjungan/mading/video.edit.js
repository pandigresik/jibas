vided_InitVideoEdit = function() {
	
}

vided_BackToVideoView = function() {
	
	vid_ShowVideoView();
	
}

vided_CheckMsgLength = function() {
	
	var msg = $("#vided_pesan").val();
	var length = $.trim(msg).length;
	var remain = vidinp_MaxNotesLength - length;

	if (remain < 0)
	{
		remain = 0;
		msg = msg.substring(0, vidinp_MaxNotesLength);
	}
	
	$("#vided_sisa").val(remain);
	$("#vided_pesan").val(msg);
}

vided_GetFileExtension = function(name) {
	
    var found = String(name).lastIndexOf(".") + 1;
    return (found > 0 ? name.substr(found) : "");
}

vided_CheckVideoType = function(ext) {

	ext = ext.toLowerCase();	
	var found = false;
	for(var i = 0; !found && i < vidinp_AllowedVideoType.length; i++)
	{
		found = ext == vidinp_AllowedVideoType[i];
	}
	
	return found;
}

vided_SaveVideo = function() {
	
	var isok = Validator.CheckLength($("#vided_judul"), "Judul Pesan", 5, 100) &&
			   Validator.CheckLength($("#vided_pesan"), "Isi Pesan", 5, vidinp_MaxNotesLength);
	
	if (!isok)
		return;
	
	var formData = new FormData();
    formData.append("videoid", $("#vided_videoid").val());
	formData.append("judul", $("#vided_judul").val());
	formData.append("pesan", mad_ChangeNewLine($("#vided_pesan").val()));
    
    var newvideo = $('#vided_new_video_file').val();
    if ($.trim(newvideo).length != 0)
    {
        var ext = "." + vided_GetFileExtension(newvideo);
		if (!vided_CheckVideoType(ext))
		{
			alert(newvideo + ' bukan file video yang diperbolehkan!');
			return;
		}
        
        formData.append("newvideo", 1);
        formData.append("newvideo_file", $("#vided_new_video_file")[0].files[0]);
        formData.append("newvideo_info", "");
    }
    else
    {
        formData.append("newvideo", 0);
    }
    	
	$("#vided_WaitBox").css({"visibility":"visible"});
	$('#vided_SaveButton').attr("disabled", true);
	
	//alert("READY");
	//return;

	$.ajax({
        url: "video.edit.save.php",
        type: 'POST',
        data: formData,
        async: false,
		cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
			$("#vided_WaitBox").css({"visibility":"hidden"});
			
			vid_ShowVideoView();
			vidvw_RefreshVideoView($("#vided_videoid").val())
			vidvw_SetViewHasChange();
			
            //$("#vided_content").html(response);
        },
		error: function (xhr, response, error)
		{
			$('#vided_SaveButton').attr("disabled", false);
			$("#vided_WaitBox").css({"visibility":"hidden"});
			
			alert(xhr.responseText);
		}
    });
	
}