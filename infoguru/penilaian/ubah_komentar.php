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
if (isset($_REQUEST['komentar']))
	$komentar = $_REQUEST['komentar'];
$status='tambah';
if (isset($_REQUEST['status']))
	$status = $_REQUEST['status'];
if ($status=='tambah')
	$title = 'Tambah';
else
	$title = 'Ubah';
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
<TITLE>JIBAS INFOGURU [<?=$title?> Komentar]</TITLE>
<META NAME="Generator" CONTENT="EditPlus">
<META NAME="Author" CONTENT="">
<META NAME="Keywords" CONTENT="">
<META NAME="Description" CONTENT="">
<link rel="stylesheet" type="text/css" href="../style/style.css">
<script language="javascript" type="text/javascript" src="../script/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<!--<script language="javascript" type="text/javascript" src="../script/tiny_mce_lama/tiny_mce.js"></script>-->
    <script language="javascript" type="text/javascript">
	/*tinyMCE.init({
	theme : "advanced",
	mode : "textareas",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",	
	theme_advanced_disable : "sup,sub,help,removeformat,hr,separator,visualaid,charmap",
	theme_advanced_toolbar_align : "center",
	width : "200",
	height: "200"
});
	*/
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

<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" style=" background-image:url(../images/bgpop.jpg); background-repeat:repeat-x">

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
    <td colspan="3" class="header"><div align="center"><?=$title?> Komentar</div></td>
    </tr>
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
    <td><textarea name="komentar" id="komentar"  rows="10" cols="100" >
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
</BODY>
</HTML>
<?php
CloseDb();
?>