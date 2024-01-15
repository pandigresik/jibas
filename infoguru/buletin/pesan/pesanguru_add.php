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
<script language="javascript" type="text/javascript" src="../../script/tinymce2/jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
tinyMCE.init({
		mode : "exact",
		theme : "advanced",
        elements : "pesan", 
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
	
	
function validate(){
	var judul=document.getElementById('judul').value;
	var pesan=tinyMCE.get('pesan').getContent();
	if (judul.length==0){
		alert ('Anda harus mengisikan data untuk Judul Pesan');
		document.getElementById('judul').focus();
		return false;
	}
	if (pesan.length==0){
		alert ('Anda harus mengisikan data untuk Pesan');
		document.getElementById('pesan').focus();
		return false;
	}
	simpan();
}
function hapusfile(field){
	document.getElementById(field).value="";
}
function simpan(){
	document.getElementById('receiver').value="";;
	var jumpeg=parent.tujuan_footer.document.getElementById('numpegawai').value;
	var numpegawaikirim=parent.tujuan_footer.document.getElementById('numpegawaikirim').value;
	var tahun=parent.tujuan_header.document.getElementById('tahun').value;
	var bulan=parent.tujuan_header.document.getElementById('bulan').value;
	//var bagian=parent.tujuan_header.document.getElementById('bagian').value;
	document.getElementById('tahun').value=tahun;
	document.getElementById('bulan').value=bulan;
	document.getElementById('jum').value=numpegawaikirim;
	var x=1;
	while (x<=jumpeg){
		var nip = parent.tujuan_footer.document.getElementById('nip'+x).value;
		var ceked = parent.tujuan_footer.document.getElementById('ceknip'+x).checked;
		if (ceked==true){
		var rec=document.getElementById('receiver').value;
		document.getElementById('receiver').value=rec+nip+"|";
		}
	x++;
	}
	document.getElementById('pesanguru').submit();
}
</script>
<style type="text/css">
<!--
.style1 {color: #333333}
-->
</style>
</head>
<body onload="document.getElementById('judul').focus();">
<form name="pesanguru" id="pesanguru" action="pesangurusimpan.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="bulan" id="bulan" value="<?=$bulan?>" />
<input type="hidden" name="jum" id="jum" value="<?=$jum?>"/>
<input type="hidden" name="op" id="op" value="<?=$op?>"/>
<input type="hidden" name="receiver" id="receiver" size="300"/>
<input type="hidden" name="tahun" id="tahun" value="<?=$tahun?>" />
<input type="hidden" name="sender" id="sender" value="tambah" />
<input type="hidden" name="tanggal" id="tanggal" value="<?=date('d')."-".date('m')."-".date('Y'); ?>" />
<fieldset><legend style="background-color:#FF6600; color:#FFFFFF; font-size:14px; padding:3px 0px 3px 0px">&nbsp;Tulis pesan untuk Guru&nbsp;</legend>
<table width="100%" border="0" cellspacing="0" align="center">
  <tr>
    <td scope="row" align="left">
    <table width="100%" border="0" cellspacing="2" cellpadding="2"  >
  <tr >
    <th width="75" align="left" scope="row">Judul</th>
    <td><input type="text" maxlength="254" name="judul" id="judul" size="50" /></td>
  </tr>
  <tr>
		<th colspan="2" valign="top" align="left" scope="row"  ><div align="left">Pesan<br />
        <textarea name="pesan" rows="25" id="pesan" style="width:100%"></textarea>
    </div></th>
    </tr>
  <tr>
    <th colspan="2" scope="row">      </th>
    </tr>
  <tr>
    <th colspan="2" scope="row"></th>
    </tr>
</table>
    </td>
  </tr>
</table>
</fieldset>
</form>
</body>
</html>
<script language="javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("judul");
</script>