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
//require_once('../include/errorhandler.php');
require_once('../include/sessioninfo.php');
require_once('../include/common.php');
require_once('../include/theme.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
OpenDb();
if (isset($_REQUEST['replid']))
	$replid = $_REQUEST['replid'];
if (isset($_REQUEST['state']))
	$state = $_REQUEST['state'];
if ($state=='1') {
	$title = "Ubah Komentar";
} else {
	$title = "Tambah Komentar";
}

if (isset($_REQUEST['komentar']))
	$komentar = $_REQUEST['komentar'];
//echo $komentar;
if (isset($_REQUEST['Simpan']))
{
	//$simpan=$_REQUEST['Simpan'];
	
$komentar=$_REQUEST['komentar'];
$sql_update="UPDATE jbsakad.komennap SET komentar='$komentar' WHERE replid='".$_REQUEST['replid']."'";

$result_update=QueryDb($sql_update);
//echo $sql_update;
if ($result_update){
?>
<script language="javascript" type="text/javascript">
//alert ('Berhasil menyimpan komentar');
parent.opener.refresh();
window.close();
</script>
<?php
}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE>Ubah Komentar</TITLE>
<META NAME="Generator" CONTENT="EditPlus">
<META NAME="Author" CONTENT="">
<META NAME="Keywords" CONTENT="">
<META NAME="Description" CONTENT="">
<link rel="stylesheet" type="text/css" href="../style/style.css">
<script language="javascript" type="text/javascript" src="../script/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		theme : "simple",
	});
	
	function OpenUploader() {
	    var addr = "UploaderMain.aspx";
	    newWindow(addr, 'Uploader','720','630','resizable=1,scrollbars=1,status=0,toolbar=0');
    }
	
</script>
</HEAD>

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style="background-color:#dcdfc4">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr height="58">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_01.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_02a.jpg">
	<div align="center" style="color:#FFFFFF; font-size:16px; font-weight:bold">
    .: <?=$title?> :.
    </div>
	</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_03.jpg">&nbsp;</td>
</tr>
<tr height="150">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_04a.jpg">&nbsp;</td>
    <td width="0" style="background-color:#FFFFFF">
    <!-- CONTENT GOES HERE //--->
<?php
$sql_get_comment="SELECT k.komentar,s.nama,k.nis FROM jbsakad.komennap k, jbsakad.siswa s WHERE k.nis=s.nis AND k.replid='$replid'";
$result_get_comment=QueryDb($sql_get_comment);
$row_get_comment=@mysqli_fetch_row($result_get_comment);
$ada_get_comment=@mysqli_num_rows($result_get_comment);
//echo $sql_get_comment;
?>
<form name="frm_komentar" id="frm_komentar" action="ubah_komentar.php" method="POST">
<table width="100%" border="0" height="100%">
<tr>
    <td valign="bottom">
    <table width="100%" border="1" height="100%">
  <tr>
    <td width="7%"><strong>NIS</strong></td>
    <td width="1%"><strong>:</strong></td>
    <td width="92%"><strong>
      <?=$row_get_comment[2]?>
    </strong></td>
    </tr>
  <tr>
    <td><strong>Nama</strong></td>
    <td><strong>:</strong></td>
    <td><strong>
      <?=$row_get_comment[1]?>
    </strong></td>
    </tr>
</table>

    </td>
  </tr>
  <tr valign="top">
    <td><textarea name="komentar" id="komentar" rows="10" cols="100" style="width:100%" >
	<?=$row_get_comment[0]?>
	</textarea>
    <input type="hidden" name="replid" id="replid" value="<?=$replid?>">
    </td>
  </tr>
   <tr valign="top">
    <td><div align="center">
      <input name="Simpan" type="submit" class="but" id="Simpan" value="Simpan" style="width:100px">
      &nbsp;
      <input name="Tutup" type="button" class="but" id="Tutup" onClick="window.close()" value="Tutup" style="width:100px">
    </div></td>
  </tr>
</table>

</form>
 <!-- END OF CONTENT //--->
    </td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_06a.jpg">&nbsp;</td>
</tr>
<tr height="28">
	<td width="28" background="../<?=GetThemeDir() ?>bgpop_07.jpg">&nbsp;</td>
    <td width="*" background="../<?=GetThemeDir() ?>bgpop_08a.jpg">&nbsp;</td>
    <td width="28" background="../<?=GetThemeDir() ?>bgpop_09.jpg">&nbsp;</td>
</tr>
</table>
</BODY>
</HTML>
<?php
CloseDb();
?>