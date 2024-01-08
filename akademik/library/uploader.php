<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 * 
 * @version: 29.0 (Sept 20, 2023)
 * @notes: JIBAS Education Community will be managed by Yayasan Indonesia Membaca (http://www.indonesiamembaca.net)
 * 
 * Copyright (C) 2009 Yayasan Indonesia Membaca (http://www.indonesiamembaca.net)
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 **[N]**/ ?>
<?php
require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/theme.php');
require_once('../cek.php');
require_once('uploader.func.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="stylesheet" type="text/css" href="../style/tooltips.css">
<script language="javascript" src="../script/tables.js"></script>
<script language="javascript" src="../script/tools.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/tooltips.js"></script>
<script language = "javascript" type = "text/javascript" src="../script/jquery-1.9.1.js"></script>
<script language="javascript">

var allowedPictType = ['.jpg', '.jpeg', '.png', '.gif', '.bmp'];

showUploadDialog = function()
{
    $.ajax({
        url: "uploader.ajax.php",
        data: "op=showuploaddialog",
        success: function(html) {
			$("#divContent").empty().html(html);
		},
		error: function(xhr, options, error) {
			alert(xhr.responseText);
		}
    })
}

getFileExtension = function(name) {
	
    var found = String(name).lastIndexOf(".") + 1;
    return (found > 0 ? name.substr(found) : "");
}

checkPictType = function(ext) {

	ext = ext.toLowerCase();
    
	var found = false;
	for(var i = 0; !found && i < allowedPictType.length; i++)
	{
		found = ext == allowedPictType[i];
	}
	
	return found;
}

uploadPict = function()
{
    var gambar = $("#gambar").val();
	if ($.trim(gambar).length == 0)
	{
		alert('Anda belum memilih file gambar!');
		$("#gambar").focus();
        
		return;
	}
	
	var ext = "." + getFileExtension(gambar);
	if (!checkPictType(ext))
	{
		alert(gambar + ' bukan file gambar yang diperbolehkan!');
        
		return;
	}
    
    $("#btUpload").attr("disabled", true);
    $("#lbInfo").html("sedang mengupload ...");
    
	var departemen = $("#departemen").val();
    var deskripsi = $.trim($("#deskripsi").val());
    var formData = new FormData();
	formData.append("gambar", $("#gambar")[0].files[0]);
	formData.append("deskripsi", deskripsi);
    formData.append("departemen", departemen);
    
    $.ajax({
        url: "uploader.upload.func.php",
        type: 'POST',
        data: formData,
        async: false,
		cache: false,
        contentType: false,
        processData: false,
        success: function (response)
		{
			showList();
        },
		error: function (xhr, response, error)
		{
            $("#btUpload").attr("disabled", false);
            $("#lbInfo").html("GAGAL");
			
			alert(xhr.responseText);
		}
    });
}

showList = function()
{
	var departemen = $("#departemen").val();
	var bulan = $("#cbBulan").val();
	var tahun = $("#cbTahun").val();
	
	$.ajax({
		url: "uploader.ajax.php",
		data: "op=showlist&departemen="+departemen+"&bulan="+bulan+"&tahun="+tahun,
		type: 'POST',
		success: function(response)
		{
			$("#divContent").html(response);
        },
		error: function (xhr, response, error)
		{
			alert(xhr.responseText);
		}
	});
}

selectPict = function(addr)
{
	opener.accept(addr);
	window.close();
}

$( document ).ready(function() {

	showList();
	
});

</script>
<title>JIBAS SIMAKA [Choose & Upload]</title>
</head>

<body>

<input type="hidden" id="departemen" value="<?= $_SESSION["uploaddept"] ?>">
<table border='0' cellpadding='5' cellspacing='0' width='100%'>
<tr>
    <td align='left'>
        Bulan: <?php ShowCbBulan() ?> <?php ShowCbTahun() ?>
		<input type='button' class='but' value='Lihat' onclick='showList()'>        
        <input type='button' class='but' value='Upload' onclick='showUploadDialog()'>        
    </td>
</tr>
<tr>
    <td align='left'>
        <div id='divContent'>
            
        </div>
    </td>
</tr>
</table>

</body>
</html>