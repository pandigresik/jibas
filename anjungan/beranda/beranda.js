b_Edit = function() {
    $.ajax({
        url : 'beranda/beranda.login.php',
        type: 'get',
        data: 'op=edit',
        success : function(html) {
            $('#b_main').html(html);
        }
    })
}

b_ChangeBackground = function() {
    
    $.ajax({
        url : 'beranda/beranda.login.php',
        type: 'get',
        data: 'op=changebackground',
        success : function(html) {
            $('#b_main').html(html);
        }
    })
    
}

b_Login = function() {
    
    var op = trim($('#b_op').val());
    var password = trim($('#b_password').val());
    if (password.length == 0)
        return;
    
    $.ajax({
        url : 'beranda/beranda.dologin.php',
        type: 'get',
        data: 'op='+op+'&password='+password,
        success : function(html) {
            $('#b_main').html(html);
            
            if (op == 'changebackground')
                initLytebox();
        }
    })
}

b_Save = function() {
    if (!confirm('Apakah isi data sudah benar?'))
        return;
    
    var content = tinyMCE.get('b_content').getContent();
    content = escape(content);
    $.ajax({
        url : 'beranda/beranda.save.php',
        data : 'content='+content,
        type: 'post',
        success : function(html) {
            $('#b_main').html(html);
        }
    })
}

b_Close = function() {
    $.ajax({
        url : 'beranda/beranda.logout.php',
        type: 'get',
        success : function(html) {
            $('#b_main').html(html);
        }
    })
}

b_Cancel = function() {
    $.ajax({
        url : 'beranda/beranda.content.php',
        type: 'get',
        success : function(html) {
            $('#b_main').html(html);
        }
    })
}

b_addNewPicture = function() {
    
    var nid = parseInt($("#b_new_ngambar").val()) + 1;
	
	var row = "<tr id='b_new_row" + nid + "'>\r\n";
	row += "<td align='left'><input type='file' id='b_new_gambar_file" + nid + "' name='b_new_gambar_file" + nid + "' style='width: 500px; height: 32px;' class='inputbox'>\r\n";
	row += "<input type='button' class='but' value=' X ' style='height: 26px;' onclick='b_delNewPicture(" + nid + ")'>\r\n";
	row += "</td>\r\n";
	row += "</tr>\r\n";
	
	$("#b_new_tabGambar > tbody:last").append(row);
	$("#b_new_ngambar").val(nid);
    
}

b_delNewPicture = function(rowno) {
	
	if (!confirm('Apakah anda akan menghapus gambar ini?'))
		return;
	
	$("#b_new_row" + rowno).remove();
    
}

b_DelPicture = function(rowno)
{
    
    if (!confirm('Apakah anda akan menghapus gambar ini?'))
		return;
	
	$("#b_row" + rowno).remove();
    $("#b_isdel" + rowno).val(1);
    
}

b_CheckPictType = function(ext) {

    var allowedPictType = ['.jpg', '.jpeg', '.png', '.gif', '.bmp'];
    
	ext = ext.toLowerCase();	
	var found = false;
	for(var i = 0; !found && i < allowedPictType.length; i++)
	{
		found = ext == allowedPictType[i];
	}
	
	return found;
}

b_GetFileExtension = function(name) {
	
    var found = String(name).lastIndexOf(".") + 1;
    return (found > 0 ? name.substr(found) : "");
}

b_SavePicture = function()
{
    var formData = new FormData();
    var delay = $.trim($('#b_delay').val());
    if (delay.length == 0)
    {
        alert("Anda belum mengisikan jeda waktu penampilan gambar latar!");
        $('#b_delay').focus();
        return;
    }
    if (isNaN(delay))
    {
        alert("Jeda waktu penampilan gambar latar harus angka!");
        $('#b_delay').focus();
        return;
    }
    
    formData.append('delay', delay);

    var ngambar = parseInt($('#b_ngambar').val());
    var ndelete = 0;
    var nkeep = 0;
    for(var i = 1; i <= ngambar; i++)
    {
        var gambar = $('#b_filename' + i).val();
        var isdel = $('#b_isdel' + i).val();
        if (isdel == 1)
        {
            formData.append('dellist' + ndelete, gambar);
            ndelete += 1;
        }
        else
        {
            formData.append('keeplist' + nkeep, gambar);
            nkeep += 1;
        }
    }
    formData.append('ndelete', ndelete);
    formData.append('nkeep', nkeep);
    
    ngambar = parseInt($('#b_new_ngambar').val());
    nnew = 0;
    for(var i = 1; i <= ngambar; i++)
    {
        if ($('#b_new_row' + i).length == 0)
            continue;
        
        gambar = $('#b_new_gambar_file' + i).val();
        if ($.trim(gambar).length == 0)
            continue;
        
        var ext = "." + b_GetFileExtension(gambar);
		if (!b_CheckPictType(ext))
		{
			alert(gambar + ' bukan file gambar yang diperbolehkan!');
			return;
		}
        
        nnew += 1;
		formData.append("gambar" + nnew, $("#b_new_gambar_file" + i)[0].files[0]);
    }
    formData.append('nnew', nnew);
    
    $('#btnSavePicture').attr("disabled", true);
    $.ajax({
        url: "beranda/beranda.savebg.php",
        type: 'POST',
        data: formData,
        async: false,
		cache: false,
        contentType: false,
        processData: false,
        success: function (response) {
			//$('#b_main').html(response);
            
            $.ajax({
                url : 'beranda/beranda.content.php',
                type: 'get',
                success : function(html) {
                    $('#b_main').html(html);
                }
            })
            
            alert('Konfigurasi gambar latar telah disimpan.\r\nSilahkan muat ulang halaman Anjungan Informasi.');
        },
		error: function (xhr, response, error)
		{
			$('#btnSavePicture').attr("disabled", false);
			
			alert(xhr.responseText);
		}
    });
}