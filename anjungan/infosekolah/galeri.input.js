galinp_BackRefreshGalleryList = function() {
	
}

galinp_CheckMsgLength = function() {
	
	var msg = $("#galinp_pesan").val();
	var length = $.trim(msg).length;
	var remain = galinp_MaxNotesLength - length;

	if (remain < 0)
	{
		remain = 0;
		msg = msg.substring(0, galinp_MaxNotesLength);
	}
	
	$("#galinp_sisa").val(remain);
	$("#galinp_pesan").val(msg);
}

galinp_addPicture = function() {
	
	var nid = parseInt($("#galinp_ngambar").val()) + 1;
	
	var row = "<tr id='galinp_row" + nid + "'>\r\n";
	row += "<td align='left'><input type='file' id='galinp_gambar_file" + nid + "' name='galinp_gambar_file" + nid + "' style='width: 300px; height: 32px;' class='inputbox'></td>\r\n";
	row += "<td align='left'>\r\n";
	row += "<input type='textbox' id='galinp_gambar_info" + nid + "' name='galinp_gambar_info" + nid + "' style='width: 300px; height: 32px;' class='inputbox'>\r\n";
	row += "<input type='button' class='but' value=' X ' style='height: 26px;' onclick='galinp_delPicture(" + nid + ")'>\r\n";
	row += "</td>\r\n";
	row += "</tr>\r\n";
	
	$("#galinp_tabGambar > tbody:last").append(row);
	$("#galinp_ngambar").val(nid);
}

galinp_delPicture = function(rowno) {
	
	if (!confirm('Apakah anda akan menghapus gambar ini?'))
		return;
	
	$("#galinp_row" + rowno).remove();
}

galinp_CheckPictType = function(ext) {

	ext = ext.toLowerCase();	
	var found = false;
	for(var i = 0; !found && i < galinp_AllowedPictType.length; i++)
	{
		found = ext == galinp_AllowedPictType[i];
	}
	
	return found;
}

galinp_GetFileExtension = function(name) {
	
    var found = String(name).lastIndexOf(".") + 1;
    return (found > 0 ? name.substr(found) : "");
}

galinp_IsValidUrl = function(url) {
	
    return url.match(/^(ht|f)tps?:\/\/[a-z0-9-\.]+\.[a-z]{2,4}\/?([^\s<>\#%"\,\{\}\\|\\\^\[\]`]+)?$/);
}

galinp_ValidateNotes = function() {
	
	var isok = Validator.CheckLength($("#galinp_judul"), "Judul Galeri", 3, 100) &&
			   Validator.CheckLength($("#galinp_pesan"), "Keterangan", 3, galinp_MaxNotesLength);
	
	if (!isok)
		return;
	
	var file = $("#galinp_cover_file").val();
	if ($.trim(file).length == 0)
	{
		alert('Anda belum menentukan gambar cover!');
		$("#galinp_cover_file").focus();
		return;
	}
	
	var ext = "." + galinp_GetFileExtension(file);
	if (!galinp_CheckPictType(ext))
	{
		alert(file + ' bukan file gambar yang diperbolehkan!');
		return;
	}
	
	var count = 0;
	var ngambar = parseInt($("#galinp_ngambar").val());
	for(var i = 1; i <= ngambar; i++)
	{
		if ($('#galinp_row' + i).length == 0)
			continue;
		
		file = $("#galinp_gambar_file" + i).val();
		if ($.trim(file).length == 0)
			continue;
		
		ext = "." + galinp_GetFileExtension(file);
		if (!galinp_CheckPictType(ext))
		{
			alert(file + ' bukan file gambar yang diperbolehkan!');
			return;
		}
		
		count += 1;
	}
	
	if (count == 0)
	{
		alert('Anda harus memilih minimal satu file gambar!');
		return;
	}
	
	var login = $.trim($("#galinp_Login").val());
    var password = $.trim($("#galinp_Password").val());
	var dept = $.trim($gal_jQueryParent("#ifse_departemen").val());
	if (login.length == 0 || password.length == 0)
	{
		alert("Anda belum mengisikan login atau password!");
		return;
	}
		
	$.ajax({
		type: 'POST',
		url: 'galeri.input.ajax.php',
		data: 'op=validatelogin&dept='+dept+'&login='+login+'&password='+password,
		success: function(html) {
			galinp_SaveGallery();
		},
		error: function(xhr, response, error) {
			alert(xhr.responseText);
		}
	})
	
}

galinp_SaveGallery = function() {
	
	var formData = new FormData();
	formData.append("departemen", $gal_jQueryParent("#ifse_departemen").val());
	formData.append("login", $.trim($("#galinp_Login").val()));
	formData.append("password", $.trim($("#galinp_Password").val()));
	formData.append("judul", $("#galinp_judul").val());
	formData.append("keterangan", ifse_ChangeNewLine($("#galinp_pesan").val()));
	formData.append("cover_file", $("#galinp_cover_file")[0].files[0]);
	formData.append("cover_info", $("#galinp_cover_info").val());
	
	var ngambar = parseInt($("#galinp_ngambar").val());
	var cnt = 0;
	for(var i = 1; i <= ngambar; i++)
	{
		if ($('#galinp_row' + i).length == 0)
			continue;
		
		var file = $("#galinp_gambar_file" + i).val();
		if ($.trim(file).length == 0)
			continue;
		
		cnt += 1;
		formData.append("gambar_file_" + cnt, $("#galinp_gambar_file" + i)[0].files[0]);
		formData.append("gambar_info_" + cnt, $("#galinp_gambar_info" + i).val());
	}
	formData.append("ngambar", cnt);
		
	$("#galinp_WaitBox").css({"visibility":"visible"});
	$('#galinp_SaveButton').attr("disabled", true);

	$.ajax({
        url: "galeri.input.save.php",
        type: 'POST',
        data: formData,
        async: false,
		cache: false,
        contentType: false,
        processData: false,
        success: function (response)
		{
			$("#galinp_WaitBox").css({"visibility":"hidden"});
			//$("#galinp_content").html(response);
			
			galinp_BackToGalleryList(true);
        },
		error: function (xhr, response, error)
		{
			$('#galinp_SaveButton').attr("disabled", false);
			$("#galinp_WaitBox").css({"visibility":"hidden"});
			
			alert(xhr.responseText);
		}
    });
	
}

galinp_BackToGalleryList = function(showtop)
{
	gallst_RefreshGalleryList();
	if (showtop)
		gal_ShowGalleryList_TopPos();
	else
		gal_ShowGalleryList_LastPos();
	
}