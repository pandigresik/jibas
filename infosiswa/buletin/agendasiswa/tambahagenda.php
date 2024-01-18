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
require_once('../../include/common.php');
require_once('../../include/sessioninfo.php');
require_once('../../include/config.php');
require_once('../../include/getheader.php');
require_once('../../include/db_functions.php');
$tanggal=$_REQUEST['tanggal'];
if (isset($_REQUEST['simpan'])){
OpenDb();
$judul=$_REQUEST['judul'];
$komentar=$_REQUEST['komentar'];
$tgl=explode("-",(string) $_REQUEST['tanggal']);
$tanggal=$tgl[2]."-".$tgl[1]."-".$tgl[0];
$idguru=SI_USER_ID();
$sql="INSERT INTO jbsvcr.agenda SET tanggal='$tanggal',judul='$judul',komentar='$komentar',idguru=NULL,nis='$idguru'";
$result=QueryDb($sql);
CloseDb();
if ($result){
?>
<script language="javascript">
	opener.get_fresh('<?=$tgl[1]?>','<?=$tgl[2]?>');
	window.close();
</script>
<?php
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../../style/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../../style/calendar-win2k-1.css">
<title>Tambah Agenda Siswa</title>
<script type="text/javascript" src="../../script/calendar.js"></script>
<script type="text/javascript" src="../../script/lang/calendar-en.js"></script>
<script type="text/javascript" src="../../script/calendar-setup.js"></script>
<script language="javascript" type="text/javascript" src="../../script/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
		skin : "o2k7",
		skin_variant : "silver",
		plugins : "safari,pagebreak,style,layer,table,save,advhr,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",		
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "forecolor,backcolor,fullscreen,print,|,cut,copy,paste,pastetext,|,search,replace,|,bullist,numlist,|,hr,removeformat,|,sub,sup,|,charmap,image",
		theme_advanced_buttons3 : "tablecontrols",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : false,
		content_css : "../../style/content.css"
	});
	</script>
</head>
<body onLoad="document.getElementById('judul').focus();" leftmargin="0" topmargin="0">
<form name="agenda" id="agenda" action="tambahagenda.php" method="POST" onSubmit="return validate()">
<table width="100%" border="0" cellspacing="5" cellpadding="2" >
  <tr>
    <td colspan="2" scope="row" align="left"><strong><font size="2" color="#999999">Agenda Baru :</font></strong><br /><br /></td>
  </tr>
  <tr>
    <td scope="row"><div align="left"><strong>Tanggal</strong></div></td>
    <td scope="row"><div align="left">
      <input title="Klik untuk membuka kalender !" type="text" name="tanggal" id="tanggal" size="25" readonly="readonly" class="disabled" value="<?=$tanggal; ?>"/>
    <img title="Klik untuk membuka kalender !" src="../../images/ico/calendar_1.png" name="btntanggal" width="16" height="16" border="0" id="btntanggal"/></div></td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF" scope="row"><div align="left"><strong>Judul</strong></div></td>
    <td bgcolor="#FFFFFF" scope="row"><div align="left">
      <input type="text" name="judul" id="judul" size="50" />
    </div></td>
  </tr>
  <tr>
    <th colspan="2" valign="top" scope="row"><fieldset><legend>Deskripsi</legend><textarea name="komentar" rows="20" id="komentar"></textarea></fieldset></th>
    </tr>
  <tr>
    <th colspan="2" scope="row" align="center"><input title="Simpan agenda !" type="submit" class="but" name="simpan" id="simpan" value="Simpan" />&nbsp;&nbsp;
    <input title="Tutup !" type="button" class="but" onClick="window.close();" name="tutup" id="tutup" value="Tutup" /></th>
  </tr>
</table>
</form>

</body>
<script type="text/javascript">
  Calendar.setup(
    {
	  inputField  : "tanggal",         
      ifFormat    : "%d-%m-%Y",  
      button      : "btntanggal"    
    }
   );
   Calendar.setup(
    {
	  inputField  : "tanggal",      
      ifFormat    : "%d-%m-%Y",   
      button      : "tanggal"     
    }
   );
  
</script>
</html>