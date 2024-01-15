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
require_once('../../include/sessionchecker.php');

$bulan="";
if (isset($_REQUEST['bulan']))
	$bulan=$_REQUEST['bulan'];
$tahun="";
if (isset($_REQUEST['tahun']))
	$tahun=$_REQUEST['tahun'];
$idguru=SI_USER_ID();	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="../../style/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../../style/calendar-win2k-1.css">
<script type="text/javascript" src="../../script/calendar.js"></script>
<script type="text/javascript" src="../../script/lang/calendar-en.js"></script>
<script type="text/javascript" src="../../script/calendar-setup.js"></script>
<script language="javascript" src="../../script/tables.js"></script>
<script src="../../script/SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="../../script/SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="../../script/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
tinyMCE.init({
		mode : "exact",
		theme : "advanced",
        elements : "isi", 
		skin : "o2k7",
		skin_variant : "silver",
		plugins : "safari,pagebreak,style,layer,table,save,advhr,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",		
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect,forecolor,backcolor,fullscreen,print",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,|,search,replace,|,bullist,numlist,|,hr,removeformat,|,sub,sup,|,charmap,image,|,tablecontrols",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : false,
		content_css : "../../style/content.css"
	});

tinyMCE.init({
		mode : "exact",
		theme : "simple",
        elements : "abstrak"  
});

function validate(){
	var judul=document.getElementById('judul').value;
	var abstrak=tinyMCE.get('abstrak').getContent();
	var isi=tinyMCE.get('isi').getContent();
	if (judul.length==0){
		alert ('Anda harus mengisikan data untuk Judul Berita');
		document.getElementById('judul').focus();
		return false;
	}
	if (abstrak.length==0){
		alert ('Anda harus mengisikan data untuk Abstraksi Berita');
		document.getElementById('abstrak').focus();
		return false;
	}
	if (isi.length==0){
		alert ('Anda harus mengisikan data untuk Isi Berita');
		document.getElementById('isi').focus();
		return false;
	}
	return true;
}

function hapusfile(field){
	document.getElementById(field).value="";
}
</script>
</head>
<body onload="document.getElementById('judul').focus();">
<form name="beritasiswa" id="beritasiswa" action="beritasiswa_add_simpan.php" method="post" onsubmit="return validate()" enctype="multipart/form-data">
<input type="hidden" name="bulan" id="bulan" value="<?=$bulan?>" />
<input type="hidden" name="tahun" id="tahun" value="<?=$tahun?>" />
<input type="hidden" name="sender" id="sender" value="tambah" />
<table width="100%" border="0" cellspacing="0">
  <tr>
    <td scope="row" align="left"><strong><font size="2" color="#999999">Berita Baru :</font></strong><br /><br /></td>
  </tr>
  <tr>
    <td scope="row" align="left">
    <table width="100%" border="0" cellspacing="2" cellpadding="2" >
  <tr>
    <th width="8%" align="right" scope="row">Judul</th>
    <td colspan="2"><input type="text" name="judul" id="judul" size="50" maxlength="254" /></td>
  </tr>
  <tr>
    <th align="right" scope="row">Tanggal</th>
    <td colspan="2"><input title="Klik untuk membuka kalender !" type="text" name="tanggal" id="tanggal" size="25" readonly="readonly" class="disabled" value="<?=date('d')."-".date('m')."-".date('Y'); ?>"/><img title="Klik untuk membuka kalender !" src="../../images/ico/calendar_1.png" name="btntanggal" width="16" height="16" border="0" id="btntanggal"/></td>
  </tr>
  <tr>
    <th align="right" valign="top" scope="row">Abstrak</th>
    <td colspan="2"><textarea name="abstrak" id="abstrak" cols="100"></textarea></td>
  </tr>
  <tr>
    <th align="right" valign="top" scope="row">Isi</th>
    <td colspan="2"><textarea name="isi" id="isi" rows="30" cols="100"></textarea></td>
  </tr>
  <tr>
    <th colspan="3" scope="row" align="center" bgcolor="#FFFFFF" height="30">
      <input class="but" type="submit" name="simpan" id="simpan" value="Simpan" title="Simpan berita ini !"/>
    &nbsp;<input class="but" type="button" name="batal" id="batal" value="Batal" onclick="window.self.history.back();" title="Batalkan dan kembali ke Halaman Berita"/></th>
    </tr>
</table>
    </td>
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
<script language="javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("judul");
</script>