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
<script language="javascript" type="text/javascript" src="../../script/tinymce2/jscripts/tiny_mce/tiny_mce.js"></script>
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
	
	
	function OpenUploader() {
	    var addr = "UploaderMain.aspx";
	    newWindow(addr, 'Uploader','720','630','resizable=1,scrollbars=1,status=0,toolbar=0');
    }
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
</head>
<body onload="document.getElementById('judul').focus();">
<form name="pesanguru" id="pesanguru" action="pesangurusimpan.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="bulan" id="bulan" value="<?=$bulan?>" />
<input type="hidden" name="jum" id="jum" value="<?=$jum?>"/>
<input type="hidden" name="op" id="op" value="<?=$op?>"/>
<input type="hidden" name="receiver" id="receiver" size="300"/>
<input type="hidden" name="tahun" id="tahun" value="<?=$tahun?>" />
<input type="hidden" name="sender" id="sender" value="tambah" />
<table width="100%" border="0" cellspacing="0">
  <tr>
	<td scope="row" align="left"><span class="style1" style="background-color:#CCCCCC"><strong><font size="2">Tulis pesan untuk Guru</font></strong><br />
    </span><br /></td>  
    <!--td scope="row" align="left"><strong><font size="2" color="#999999">Pesan Baru :</font></strong><br /><br /></td-->
  </tr>
  <tr>
    <td scope="row" align="left">
    <table width="100%" border="0" cellspacing="2" cellpadding="2"  >
  <tr >
    <th width="134" scope="row"><div align="left">Judul</div></th>
    <td colspan="2"><input type="text" maxlength="254" name="judul" id="judul" size="50" /></td>
  </tr>
  <tr>
    <th scope="row"><div align="left">Tanggal Tampil</div></th>
    <td colspan="2"><input title="Klik untuk membuka kalender !" type="text" name="tanggal" id="tanggal" size="25" readonly="readonly" class="disabled" value="<?=date(d)."-".date('m')."-".date('Y'); ?>"/><img title="Klik untuk membuka kalender !" src="../../images/ico/calendar_1.png" name="btntanggal" width="16" height="16" border="0" id="btntanggal"/></td>
  </tr>
  <tr>
    <th colspan="3" valign="top" align="left" scope="row"  ><div align="left">Pesan<br />
        <textarea name="pesan" rows="25" id="pesan" style="width:100%"></textarea>
    </div></th>
    </tr>
  <tr>
    <th colspan="3" scope="row"></th>
    </tr>
  <tr>
    <th colspan="3" scope="row"></th>
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