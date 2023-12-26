not_edit_InitNotesEdit = function() {

	initLytebox();
	
}

not_edit_BackToNotesView = function() {
	
	not_ShowNotesView();
	
}

not_edit_addPicture = function() {
	
	var nid = parseInt($("#not_edit_new_ngambar").val()) + 1;
	
	var row = "<tr id='not_edit_new_gambar_row" + nid + "'>\r\n";
	row += "<td align='left'><input type='file' id='not_edit_new_gambar_file" + nid + "' name='not_edit_new_gambar_file" + nid + "' style='width: 300px; height: 32px;' class='inputbox'></td>\r\n";
	row += "<td align='left'>\r\n";
	row += "<input type='textbox' id='not_edit_new_gambar_info" + nid + "' name='not_edit_new_gambar_info" + nid + "' style='width: 300px; height: 32px;' class='inputbox'>\r\n";
	row += "<input type='button' class='but' value='(-)' style='height: 26px;' onclick='not_delPicture(" + nid + ")'>\r\n";
	row += "</td>\r\n";
	row += "</tr>\r\n";
	
	$("#not_edit_tabGambar > tbody:last").append(row);
	$("#not_edit_new_ngambar").val(nid);
}

not_edit_delPicture = function(rowno) {
	
	if (!confirm('Apakah anda akan menghapus gambar ini?'))
		return;
	
	$("#not_edit_new_gambar_row" + rowno).remove();
}

not_edit_addFile = function() {
	
	var nid = parseInt($("#not_edit_new_nfile").val()) + 1;
	
	var row = "<tr id='not_edit_new_file_row" + nid + "'>\r\n";
	row += "<td align='left'><input type='file' id='not_edit_new_file_file" + nid + "' name='not_edit_new_file_file" + nid + "' style='width: 300px; height: 32px;' class='inputbox'></td>\r\n";
	row += "<td align='left'>\r\n";
	row += "<input type='textbox' id='not_edit_new_file_info" + nid + "' name='not_edit_new_file_info" + nid + "' style='width: 300px; height: 32px;' class='inputbox'>\r\n";
	row += "<input type='button' class='but' value='(-)' style='height: 26px;' onclick='not_delFile(" + nid + ")'>\r\n";
	row += "</td>\r\n";
	row += "</tr>\r\n";
	
	$("#not_edit_tabFile > tbody:last").append(row);
	$("#not_edit_new_nfile").val(nid);
}

not_edit_delFile = function(rowno) {
	
	if (!confirm('Apakah anda akan menghapus berkas ini?'))
		return;
	
	$("#not_edit_new_file_row" + rowno).remove();
}

not_edit_CheckMsgLength = function() {
	
	var msg = $("#not_edit_pesan").val();
	var length = $.trim(msg).length;
	var remain = not_MaxNotesLength - length;
	if (remain < 0)
	{
		remain = 0;
		msg = msg.substring(0, not_MaxNotesLength);
	}
	
	$("#not_edit_sisa").val(remain);
	$("#not_edit_pesan").val(msg);
}

not_edit_CheckPictType = function(ext) {

	ext = ext.toLowerCase();	
	var found = false;
	for(var i = 0; !found && i < not_AllowedPictType.length; i++)
	{
		found = ext == not_AllowedPictType[i];
	}
	
	return found;
}

not_edit_CheckDocType = function(ext) {
	
	ext = ext.toLowerCase();	
	var found = false;
	for(var i = 0; !found && i < not_AllowedDocType.length; i++)
	{
		found = ext == not_AllowedDocType[i];
	}
	
	return found;
}

not_edit_GetFileExtension = function(name) {
	
    var found = String(name).lastIndexOf(".") + 1;
    return (found > 0 ? name.substr(found) : "");
}

not_edit_IsValidUrl = function(url)
{
    return url.match(/^(ht|f)tps?:\/\/[a-z0-9-\.]+\.[a-z]{2,4}\/?([^\s<>\#%"\,\{\}\\|\\\^\[\]`]+)?$/);
}

not_edit_SaveNotes = function() {
	
	var isok = Validator.CheckLength($("#not_edit_judul"), "Judul Pesan", 3, 100) &&
			   Validator.CheckLength($("#not_edit_kepada"), "Tujuan Pesan", 3, 100) &&
			   Validator.CheckLength($("#not_edit_pesan"), "Isi Pesan", 5, not_MaxNotesLength);
	
	if (!isok)
		return;
	
	var tautan = $("#not_edit_tautan").val();
	if ($.trim(tautan).length > 0)
	{
		if (!not_edit_IsValidUrl(tautan))
		{
			alert(tautan + " bukan format URL yang benar!");
			$("#not_edit_tautan").focus();
			return;
		}
	}
	
	var formData = new FormData();
    formData.append("notesid", $("#not_edit_notesid").val());
	formData.append("judul", $("#not_edit_judul").val());
	formData.append("kepada", $("#not_edit_kepada").val());
	formData.append("pesan", ifse_ChangeNewLine($("#not_edit_pesan").val()));
	formData.append("tautan", $("#not_edit_tautan").val());
    
    var ngambar = parseInt($("#not_edit_ngambar").val());
	var cnt = 0;
	for(var i = 1; i <= ngambar; i++)
	{
        cnt += 1;
		formData.append("edit_gambar_replid_" + cnt, $("#not_edit_gambar_replid" + i).val());
        formData.append("edit_gambar_delete_" + cnt, $("#not_edit_gambar_delete" + i).val());
        
        if ($('#not_edit_gambar_row' + i).length != 0)
			formData.append("edit_gambar_info_" + cnt, $("#not_edit_gambar_info" + i).val());
        else
            formData.append("edit_gambar_info_" + cnt, "");
	}
	formData.append("edit_ngambar", cnt);
	
	ngambar = parseInt($("#not_edit_new_ngambar").val());
	cnt = 0;
	for(var i = 1; i <= ngambar; i++)
	{
		if ($('#not_edit_new_gambar_row' + i).length == 0)
			continue;
		
		var file = $("#not_edit_new_gambar_file" + i).val();
		if ($.trim(file).length == 0)
			continue;
		
		var ext = "." + not_GetFileExtension(file);
		if (!not_edit_CheckPictType(ext))
		{
			alert(file + ' bukan file gambar yang diperbolehkan!');
			return;
		}
		
		cnt += 1;
		formData.append("gambar_file_" + cnt, $("#not_edit_new_gambar_file" + i)[0].files[0]);
		formData.append("gambar_info_" + cnt, $("#not_edit_new_gambar_info" + i).val());
	}
	formData.append("ngambar", cnt);
	
    var nfile = parseInt($("#not_edit_nfile").val());
	cnt = 0;
	for(var i = 1; i <= nfile; i++)
	{
        cnt += 1;
		formData.append("edit_file_replid_" + cnt, $("#not_edit_file_replid" + i).val());
        formData.append("edit_file_delete_" + cnt, $("#not_edit_file_delete" + i).val());
        
		if ($('#not_edit_file_row' + i).length != 0)
			formData.append("edit_file_info_" + cnt, $("#not_edit_file_info" + i).val());
        else
            formData.append("edit_file_info_" + cnt, "");
	}
	formData.append("edit_nfile", cnt);
    
	nfile = parseInt($("#not_edit_new_nfile").val());
	cnt = 0;
	for(var i = 1; i <= nfile; i++)
	{
		if ($('#not_edit_new_file_row' + i).length == 0)
			continue;
		
		var file = $("#not_edit_new_file_file" + i).val();
		if ($.trim(file).length == 0)
			continue;
		
		var ext = "." + not_GetFileExtension(file);
		if (!not_edit_CheckDocType(ext))
		{
			alert(file + ' bukan file dokumen yang diperbolehkan!');
			return;
		}
		
		cnt += 1;
		formData.append("file_file_" + cnt, $("#not_edit_new_file_file" + i)[0].files[0]);
		formData.append("file_info_" + cnt, $("#not_edit_new_file_info" + i).val());
	}
	formData.append("nfile", cnt);
	
	$("#not_edit_WaitBox").css({"visibility":"visible"});
	$('#not_edit_SaveButton').attr("disabled", true);
	
	//alert("READY");
	//return;

	$.ajax({
        url: "notes.edit.save.php",
        type: 'POST',
        data: formData,
        async: false,
		cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
			$("#not_edit_WaitBox").css({"visibility":"hidden"});
			
			not_SetVisible('not_view_content');
			not_RefreshNotesView($("#not_edit_notesid").val());
			not_SetViewHasChange();
			
            //$("#not_edit_content").html(response);
        },
		error: function (xhr, response, error)
		{
			$('#not_edit_SaveButton').attr("disabled", false);
			$("#not_edit_WaitBox").css({"visibility":"hidden"});
			
			alert(xhr.responseText);
		}
    });
	
}

not_edit_DeleteEditGambar = function(no) {
    
    if (!confirm("Apakah anda yakin akan menghapus gambar ini?"))
        return;
    
    $("#not_edit_gambar_delete"+no).val(1);
    $("#not_edit_gambar_row"+no).remove();
    
}

not_edit_DeleteEditDoc = function(no) {
    
    if (!confirm("Apakah anda yakin akan menghapus dokumen ini?"))
        return;
    
    $("#not_edit_file_delete"+no).val(1);
    $("#not_edit_file_row"+no).remove();
}