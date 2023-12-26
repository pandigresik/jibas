galed_InitGalleryEdit = function() {

	initLytebox();
	
}

galed_CheckMsgLength = function() {
	
	var msg = $("#galed_pesan").val();
	var length = $.trim(msg).length;
	var remain = galinp_MaxNotesLength - length;
	if (remain < 0)
	{
		remain = 0;
		msg = msg.substring(0, galinp_MaxNotesLength);
	}
	
	$("#galed_sisa").val(remain);
	$("#galed_pesan").val(msg);
}

galed_CheckPictType = function(ext) {

	ext = ext.toLowerCase();	
	var found = false;
	for(var i = 0; !found && i < galinp_AllowedPictType.length; i++)
	{
		found = ext == galinp_AllowedPictType[i];
	}
	
	return found;
}

galed_GetFileExtension = function(name) {
	
    var found = String(name).lastIndexOf(".") + 1;
    return (found > 0 ? name.substr(found) : "");
}

galed_addPicture = function() {
	
	var nid = parseInt($("#galed_new_ngambar").val()) + 1;
	
	var row = "<tr id='galed_new_row" + nid + "'>\r\n";
	row += "<td align='left'><input type='file' id='galed_new_gambar_file" + nid + "' name='galed_new_gambar_file" + nid + "' style='width: 300px; height: 32px;' class='inputbox'></td>\r\n";
	row += "<td align='left'>\r\n";
	row += "<input type='textbox' id='galed_new_gambar_info" + nid + "' name='galed_new_gambar_info" + nid + "' style='width: 300px; height: 32px;' class='inputbox'>\r\n";
	row += "<input type='button' class='but' value=' X ' style='height: 26px;' onclick='galed_delPicture(" + nid + ")'>\r\n";
	row += "</td>\r\n";
	row += "</tr>\r\n";
	
	$("#galed_tabGambar > tbody:last").append(row);
	$("#galed_new_ngambar").val(nid);
}

galed_delPicture = function(rowno) {
	
	if (!confirm('Apakah anda akan menghapus gambar ini?'))
		return;
	
	$("#galed_new_row" + rowno).remove();
}

galed_DeleteEditGambar = function(no) {
    
    if (!confirm("Apakah anda yakin akan menghapus gambar ini?"))
        return;
    
    $("#galed_gambar_delete"+no).val(1);
    $("#galed_gambar_row"+no).remove();
    
}

galed_BackToGalleryView = function() {
	
	gal_ShowGalleryView();
	
}

galed_SaveNotes = function() {
	
	var isok = Validator.CheckLength($("#galed_judul"), "Judul Pesan", 5, 100) &&
			   Validator.CheckLength($("#galed_pesan"), "Isi Pesan", 5, galinp_MaxNotesLength);
	
	if (!isok)
		return;
	
	var formData = new FormData();
    formData.append("galleryid", $("#galed_galleryid").val());
	formData.append("judul", $("#galed_judul").val());
	formData.append("pesan", ifse_ChangeNewLine($("#galed_pesan").val()));
    
    var newcover = $('#galed_new_cover_file').val();
    if ($.trim(newcover).length != 0)
    {
        var ext = "." + galed_GetFileExtension(newcover);
		if (!galed_CheckPictType(ext))
		{
			alert(newcover + ' bukan file gambar yang diperbolehkan!');
			return;
		}
        
        formData.append("newcover", 1);
        formData.append("newcover_file", $("#galed_new_cover_file")[0].files[0]);
        formData.append("newcover_info", $("#galed_new_cover_info").val());
    }
    else
    {
        formData.append("newcover", 0);
        formData.append("cover_info", $("#galed_cover_info").val());
    }
    
    // -- Handle Existing Picture
    var ngambar = parseInt($("#galed_ngambar").val());
	var cnt = 0;
	for(var i = 1; i <= ngambar; i++)
	{
        cnt += 1;
		formData.append("edit_gambar_replid_" + cnt, $("#galed_gambar_replid" + i).val());
        formData.append("edit_gambar_delete_" + cnt, $("#galed_gambar_delete" + i).val());
        
        if ($('#galed_gambar_row' + i).length != 0)
			formData.append("edit_gambar_info_" + cnt, $("#galed_gambar_info" + i).val());
        else
            formData.append("edit_gambar_info_" + cnt, "");
	}
	formData.append("edit_ngambar", cnt);
	
    // -- Handle Newly Selected Picture
	ngambar = parseInt($("#galed_new_ngambar").val());
	cnt = 0;
	for(var i = 1; i <= ngambar; i++)
	{
		if ($('#galed_new_row' + i).length == 0)
			continue;
		
		var file = $("#galed_new_gambar_file" + i).val();
		if ($.trim(file).length == 0)
			continue;
		
		var ext = "." + galed_GetFileExtension(file);
		if (!galed_CheckPictType(ext))
		{
			alert(file + ' bukan file gambar yang diperbolehkan!');
			return;
		}
		
		cnt += 1;
		formData.append("gambar_file_" + cnt, $("#galed_new_gambar_file" + i)[0].files[0]);
		formData.append("gambar_info_" + cnt, $("#galed_new_gambar_info" + i).val());
	}
	formData.append("ngambar", cnt);
		
	$("#galed_WaitBox").css({"visibility":"visible"});
	$('#galed_SaveButton').attr("disabled", true);
	
	//alert("READY");
	//return;

	$.ajax({
        url: "galeri.edit.save.php",
        type: 'POST',
        data: formData,
        async: false,
		cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
			$("#galed_WaitBox").css({"visibility":"hidden"});
			
			gal_ShowGalleryView();
			galvw_RefreshGalleryView($("#galed_galleryid").val())
			galvw_SetViewHasChange();
			
            //$("#galed_content").html(response);
        },
		error: function (xhr, response, error)
		{
			$('#galed_SaveButton').attr("disabled", false);
			$("#galed_WaitBox").css({"visibility":"hidden"});
			
			alert(xhr.responseText);
		}
    });
	
}