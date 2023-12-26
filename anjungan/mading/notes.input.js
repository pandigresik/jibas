not_addPicture = function() {
	
	var nid = parseInt($("#not_ngambar").val()) + 1;
	
	var row = "<tr id='not_row" + nid + "'>\r\n";
	row += "<td align='left'><input type='file' id='not_gambar_file" + nid + "' name='not_gambar_file" + nid + "' style='width: 300px; height: 32px;' class='inputbox'></td>\r\n";
	row += "<td align='left'>\r\n";
	row += "<input type='textbox' id='not_gambar_info" + nid + "' name='not_gambar_info" + nid + "' style='width: 300px; height: 32px;' class='inputbox'>\r\n";
	row += "<input type='button' class='but' value=' X ' style='height: 26px;' onclick='not_delPicture(" + nid + ")'>\r\n";
	row += "</td>\r\n";
	row += "</tr>\r\n";
	
	$("#not_tabGambar > tbody:last").append(row);
	$("#not_ngambar").val(nid);
}

not_delPicture = function(rowno) {
	
	if (!confirm('Apakah anda akan menghapus gambar ini?'))
		return;
	
	$("#not_row" + rowno).remove();
}

not_addFile = function() {
	
	var nid = parseInt($("#not_nfile").val()) + 1;
	
	var row = "<tr id='not_file_row" + nid + "'>\r\n";
	row += "<td align='left'><input type='file' id='not_file_file" + nid + "' name='not_file_file" + nid + "' style='width: 300px; height: 32px;' class='inputbox'></td>\r\n";
	row += "<td align='left'>\r\n";
	row += "<input type='textbox' id='not_file_info" + nid + "' name='not_file_info" + nid + "' style='width: 300px; height: 32px;' class='inputbox'>\r\n";
	row += "<input type='button' class='but' value=' X ' style='height: 26px;' onclick='not_delFile(" + nid + ")'>\r\n";
	row += "</td>\r\n";
	row += "</tr>\r\n";
	
	$("#not_tabFile > tbody:last").append(row);
	$("#not_nfile").val(nid);
}

not_delFile = function(rowno) {
	
	if (!confirm('Apakah anda akan menghapus berkas ini?'))
		return;
	
	$("#not_file_row" + rowno).remove();
}

not_CheckMsgLength = function() {
	
	var msg = $("#not_pesan").val();
	var length = $.trim(msg).length;
	var remain = not_MaxNotesLength - length;
	
	if (remain < 0)
	{
		remain = 0;
		msg = msg.substring(0, not_MaxNotesLength);
	}
	
	$("#not_sisa").val(remain);
	$("#not_pesan").val(msg);
}

not_CheckPictType = function(ext) {

	ext = ext.toLowerCase();	
	var found = false;
	for(var i = 0; !found && i < not_AllowedPictType.length; i++)
	{
		found = ext == not_AllowedPictType[i];
	}
	
	return found;
}

not_CheckDocType = function(ext) {
	
	ext = ext.toLowerCase();	
	var found = false;
	for(var i = 0; !found && i < not_AllowedDocType.length; i++)
	{
		found = ext == not_AllowedDocType[i];
	}
	
	return found;
}

not_GetFileExtension = function(name) {
	
    var found = String(name).lastIndexOf(".") + 1;
    return (found > 0 ? name.substr(found) : "");
}

not_IsValidUrl = function(url) {
	
    return url.match(/^(ht|f)tps?:\/\/[a-z0-9-\.]+\.[a-z]{2,4}\/?([^\s<>\#%"\,\{\}\\|\\\^\[\]`]+)?$/);
}

not_ValidateNotes = function() {
	
	var isok = Validator.CheckLength($("#not_judul"), "Judul Pesan", 3, 100) &&
			   Validator.CheckLength($("#not_kepada"), "Tujuan Pesan", 1, 100) &&
			   Validator.CheckLength($("#not_pesan"), "Isi Pesan", 3, not_MaxNotesLength);
	
	if (!isok)
		return;
	
	var tautan = $("#not_tautan").val();
	if ($.trim(tautan).length > 0)
	{
		if (!not_IsValidUrl(tautan))
		{
			alert(tautan + " bukan format URL yang benar!");
			$("#not_tautan").focus();
			return;
		}
	}
	
	var ngambar = parseInt($("#not_ngambar").val());
	for(var i = 1; i <= ngambar; i++)
	{
		if ($('#not_row' + i).length == 0)
			continue;
		
		var file = $("#not_gambar_file" + i).val();
		if ($.trim(file).length == 0)
			continue;
		
		var ext = "." + not_GetFileExtension(file);
		if (!not_CheckPictType(ext))
		{
			alert(file + ' bukan file gambar yang diperbolehkan!');
			return;
		}
	}
	
	var nfile = parseInt($("#not_nfile").val());
	for(var i = 1; i <= nfile; i++)
	{
		if ($('#not_file_row' + i).length == 0)
			continue;
		
		var file = $("#not_file_file" + i).val();
		if ($.trim(file).length == 0)
			continue;
		
		var ext = "." + not_GetFileExtension(file);
		if (!not_CheckDocType(ext))
		{
			alert(file + ' bukan file dokumen yang diperbolehkan!');
			return;
		}
	}
	
	var login = $.trim($("#not_Login").val());
    var password = $.trim($("#not_Password").val());
	var dept = $.trim($not_jQueryParent("#mad_departemen").val());
	if (login.length == 0 || password.length == 0)
	{
		alert("Anda belum mengisikan login atau password!");
		return;
	}
		
	$.ajax({
		type: 'POST',
		url: 'notes.input.ajax.php',
		data: 'op=validatelogin&dept='+dept+'&login='+login+'&password='+password,
		success: function(html) {
			not_SaveNotes();
		},
		error: function(xhr, response, error) {
			alert(xhr.responseText);
		}
	})
	
}

not_SaveNotes = function() {
	
	var formData = new FormData();
	formData.append("departemen", $not_jQueryParent("#mad_departemen").val());
	formData.append("login", $.trim($("#not_Login").val()));
	formData.append("password", $.trim($("#not_Password").val()));
	formData.append("judul", $("#not_judul").val());
	formData.append("kepada", $("#not_kepada").val());
	formData.append("pesan", mad_ChangeNewLine($("#not_pesan").val()));
	formData.append("tautan", $("#not_tautan").val());
	
	var ngambar = parseInt($("#not_ngambar").val());
	var cnt = 0;
	for(var i = 1; i <= ngambar; i++)
	{
		if ($('#not_row' + i).length == 0)
			continue;
		
		var file = $("#not_gambar_file" + i).val();
		if ($.trim(file).length == 0)
			continue;
		
		cnt += 1;
		formData.append("gambar_file_" + cnt, $("#not_gambar_file" + i)[0].files[0]);
		formData.append("gambar_info_" + cnt, $("#not_gambar_info" + i).val());
	}
	formData.append("ngambar", cnt);
	
	var nfile = parseInt($("#not_nfile").val());
	var cnt = 0;
	for(var i = 1; i <= nfile; i++)
	{
		if ($('#not_file_row' + i).length == 0)
			continue;
		
		var file = $("#not_file_file" + i).val();
		if ($.trim(file).length == 0)
			continue;

		cnt += 1;
		formData.append("file_file_" + cnt, $("#not_file_file" + i)[0].files[0]);
		formData.append("file_info_" + cnt, $("#not_file_info" + i).val());
	}
	formData.append("nfile", cnt);
	
	$("#not_WaitBox").css({"visibility":"visible"});
	$('#not_SaveButton').attr("disabled", true);

	$.ajax({
        url: "notes.input.save.php",
        type: 'POST',
        data: formData,
        async: false,
		cache: false,
        contentType: false,
        processData: false,
        success: function (response)
		{
			$("#not_WaitBox").css({"visibility":"hidden"});
			
			not_ShowNotesList_TopPos();
			not_RefreshNotesList();
        },
		error: function (xhr, response, error)
		{
			$('#not_SaveButton').attr("disabled", false);
			$("#not_WaitBox").css({"visibility":"hidden"});
			
			alert(xhr.responseText);
		}
    });
	
}