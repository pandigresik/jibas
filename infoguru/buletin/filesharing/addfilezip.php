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
require_once('../../include/errorhandler.php');
require_once('../../include/sessioninfo.php');
require_once('../../include/common.php');
require_once('../../include/config.php');
require_once('../../include/db_functions.php');
require_once('../../include/theme.php');
require_once('../../include/sessionchecker.php');
require_once('addfilezip.function.php'); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../../style/style.css">
<link rel="stylesheet" type="text/css" href="../../style/tooltips.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>JIBAS INFOGURU [Tambah File]</title>
<script language = "javascript" type = "text/javascript" src="../../script/tooltips.js"></script>
<script language="javascript" src="../../script/tables.js"></script>
<script language="javascript" src="../../script/tools.js"></script>
<script language="javascript" src="../../script/validasi.js"></script>
<script language="javascript">
function validate()
{
	var value = document.getElementById("filezip").value;
	if (value.length == 0)
	{
		alert ("Anda harus mengisikan file yang hendak di Upload!");
		return false;	
	}
	return true;
}
</script>
<style type="text/css">
<!--
.style1 {color: #0000FF}
-->
</style>
</head>

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#FFFFFF" >
<form name="main" method="POST" enctype="multipart/form-data" onSubmit="return validate()">
<input type="hidden" name="fullpath" readonly value="<?=$fullpath?>" >
<input type="hidden" name="dir" readonly value="<?=$dirfullpath?>" >
<input type="hidden" name="iddir" readonly value="<?=$iddir?>" >
<table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
<!-- TABLE CONTENT -->
<tr height="25">
<td colspan="2" align="center" class="header">Unggah &amp; Ekstrak ZIP</td>
</tr>
<tr>
  <td width="12%" align="left" bgcolor="#CCCCCC"><strong>Folder&nbsp;Tujuan&nbsp;:&nbsp;</strong></td>
  <td width="88%" align="center" bgcolor="#CCCCCC">&nbsp;<strong>(root)/<?=$fullpath?></strong></td>
</tr>
<tr>
  <td align="right"><strong>File ZIP&nbsp;:&nbsp;</strong></td>
  <td align="left"><input name="filezip" id="filezip" size="60" type="file" /></td>
</tr>
<tr>
	<td colspan="2" align="center">
    <input type="submit" name="Simpan" id="Simpan" value="Unggah dan Ekstrak ZIP" class="but" />&nbsp;
    <input type="button" name="Tutup" id="Tutup" value="Tutup" class="but" onClick="window.close()" />    </td>
</tr>
<!-- END OF TABLE CONTENT -->
</table>
</form>
<!-- Tamplikan error jika ada -->
<?php if (strlen((string) $ERROR_MSG) > 0) { ?>
<script language="javascript">
	alert('<?=$ERROR_MSG?>');
</script>
<?php } ?>

<!-- Pilih inputan pertama -->

<?php if ($cek == 1) { ?>
<script language="javascript">
	document.getElementById('urutan').focus();
</script>

<?php } ?>
</body>
</html>